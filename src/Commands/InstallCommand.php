<?php

namespace FeedTan\ClickPesa\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class InstallCommand extends Command
{
    protected $signature = 'clickpesa:install';
    protected $description = 'Install ClickPesa Laravel package';

    public function handle(): int
    {
        $this->info('Installing ClickPesa Laravel package...');

        // Publish config file
        $this->call('vendor:publish', [
            '--provider' => 'FeedTan\\ClickPesa\\ClickPesaServiceProvider',
            '--tag' => 'clickpesa-config',
        ]);

        // Publish migrations
        $this->call('vendor:publish', [
            '--provider' => 'FeedTan\\ClickPesa\\ClickPesaServiceProvider',
            '--tag' => 'clickpesa-migrations',
        ]);

        // Run migrations
        if ($this->confirm('Run migrations now?')) {
            $this->call('migrate');
        }

        // Show environment variables to add
        $this->info('Add the following environment variables to your .env file:');
        $this->line('');
        $this->line('CLICKPESA_API_URL=https://api.clickpesa.com/v1');
        $this->line('CLICKPESA_API_KEY=your_api_key');
        $this->line('CLICKPESA_API_SECRET=your_api_secret');
        $this->line('CLICKPESA_MERCHANT_ID=your_merchant_id');
        $this->line('CLICKPESA_CALLBACK_URL=' . route('clickpesa.callback'));
        $this->line('CLICKPESA_RETURN_URL=' . route('payment.success'));
        $this->line('CLICKPESA_CANCEL_URL=' . route('payment.cancel'));
        $this->line('CLICKPESA_WEBHOOK_SECRET=your_webhook_secret');
        $this->line('CLICKPESA_SANDBOX=false');
        $this->line('');

        $this->info('ClickPesa Laravel package installed successfully!');

        return Command::SUCCESS;
    }
}
