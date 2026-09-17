<?php

namespace App\Console\Commands;

use App\Services\HeaderFooterCacheService;
use Illuminate\Console\Command;

class RebuildHeaderFooterCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:rebuild-header-footer-cache {--clear : Clear the static cache instead of building it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-render and statically cache the Dynamic Header & Footer templates across all active languages and device viewports.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('clear')) {
            $this->warn('Clearing static Header & Footer cache...');
            HeaderFooterCacheService::clearCache();
            $this->info('Static cache cleared. Storefront will now render dynamically.');
            return Command::SUCCESS;
        }

        $this->info('Compiling and caching static Header & Footer templates...');
        
        $result = HeaderFooterCacheService::buildCache();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Active Languages', $result['languages_count']],
                ['Device Viewports', $result['devices_count']],
                ['Total Templates Rendered', $result['total_templates']],
                ['Build Time (ms)', $result['elapsed_ms'] . ' ms'],
                ['Built At', $result['built_at']],
                ['Cache Status', 'ACTIVE (0 DB Queries on Storefront)'],
            ]
        );

        $this->info('Static Header & Footer templates compiled and cached successfully!');

        return Command::SUCCESS;
    }
}
