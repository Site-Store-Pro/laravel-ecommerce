<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearReviewedProductsSession extends Command
{
    protected $signature = 'session:clear-reviewed-products
                            {--truncate : Truncate the entire sessions table instead}';

    protected $description = 'Remove stale reviewed_products data from all database sessions';

    public function handle(): int
    {
        if ($this->option('truncate')) {
            DB::table('sessions')->truncate();
            $this->info('All sessions truncated.');
            return self::SUCCESS;
        }

        $sessions = DB::table('sessions')->get();
        $updated = 0;

        foreach ($sessions as $session) {
            try {
                $payload = unserialize(base64_decode($session->payload));
            } catch (\Throwable) {
                continue;
            }

            if (!is_array($payload) || !isset($payload['reviewed_products'])) {
                continue;
            }

            // Remove the reviewed_products key from the session payload
            unset($payload['reviewed_products']);
            $newPayload = base64_encode(serialize($payload));
            DB::table('sessions')->where('id', $session->id)->update(['payload' => $newPayload]);
            $updated++;
        }

        $this->info("Cleared reviewed_products from {$updated} session(s).");
        return self::SUCCESS;
    }
}
