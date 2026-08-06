<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AbandonedCartService;

class SendAbandonedCartReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:send-abandoned-cart-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send 24-hour and 7-day abandoned cart reminder emails to customers.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for abandoned carts to send reminder emails...');

        $stats = AbandonedCartService::processReminders();

        $this->info("Abandoned cart reminders processed:");
        $this->line(" - 24-Hour Reminders Sent: {$stats['sent_24h']}");
        $this->line(" - 7-Day Reminders Sent:  {$stats['sent_7d']}");

        return Command::SUCCESS;
    }
}
