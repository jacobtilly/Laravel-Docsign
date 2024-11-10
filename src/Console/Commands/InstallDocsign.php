<?php

namespace JacobTilly\LaravelDocsign\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallDocsign extends Command
{
    protected $signature = 'docsign:install';
    protected $description = 'Install the Docsign package';

    public function handle()
    {
        $this->info('Publishing configuration...');
        $this->call('vendor:publish', ['--tag' => 'docsign.config']);
        $this->info('Configuration published.');

        $this->info('Creating example jobs...');
        $this->call('make:job', ['name' => 'DocumentCompleteJob']);
        $this->call('make:job', ['name' => 'PartySignJob']);
        $this->info('Jobs created.');

        $apiKey = $this->ask('Enter your Docsign API key');
        $envPath = base_path('.env');

        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            if (!str_contains($envContent, 'DOCSIGN_API_KEY')) {
                File::append($envPath, "\nDOCSIGN_API_KEY={$apiKey}\n");
                $this->info('API key added to .env file.');
            }
        } else {
            $this->error('.env file not found. Please ensure it exists and try again.');
        }

        $configPath = config_path('docsign.php');
        if (File::exists($configPath)) {
            $config = File::get($configPath);
            $config = str_replace('\\JacobTilly\\LaravelDocsign\\Jobs\\DocumentCompleteJob::class', '\\App\\Jobs\\DocumentCompleteJob::class', $config);
            $config = str_replace('\\JacobTilly\\LaravelDocsign\\Jobs\\PartySignJob::class', '\\App\\Jobs\\PartySignJob::class', $config);
            $config = str_replace("'enabled' => false,", "'enabled' => true,", $config);
            File::put($configPath, $config);
            $this->info('Configuration updated with job classes and callbacks enabled.');
        } else {
            $this->error('Configuration file not found -- please try to manually publish the package configuration and then try again.');
        }

        $this->info('Docsign package installed.');
    }
}
