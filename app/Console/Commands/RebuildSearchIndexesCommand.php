<?php

namespace App\Console\Commands;

use App\Models\CmsPage;
use App\Models\Product;
use Illuminate\Console\Command;

class RebuildSearchIndexesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:rebuild-index {--force : Force rebuild even if locked}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search index keywords for all CMS pages and products, stripping shortcodes and HTML tags.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $force = (bool) $this->option('force');

        $this->info('Rebuilding CMS Page search indexes...');
        $pageCount = 0;
        CmsPage::with(['type', 'categories', 'tags'])->chunk(50, function ($pages) use (&$pageCount, $force) {
            foreach ($pages as $page) {
                $page->rebuildSearchIndex(force: $force);
                $page->saveQuietly();
                $pageCount++;
            }
        });
        $this->info("Completed {$pageCount} CMS Pages.");

        $this->info('Rebuilding Product search indexes...');
        $prodCount = 0;
        Product::with(['brand', 'categories', 'variants'])->chunk(50, function ($products) use (&$prodCount, $force) {
            foreach ($products as $product) {
                $product->rebuildSearchIndex(force: $force);
                $product->saveQuietly();
                $prodCount++;
            }
        });
        $this->info("Completed {$prodCount} Products.");

        $this->newLine();
        $this->info('Search index rebuild finished successfully.');

        return Command::SUCCESS;
    }
}
