<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link-manual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the symbolic link from public/storage to storage/app/public (without using exec)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $target = storage_path('app/public');
        $link = public_path('storage');

        // Check if target directory exists
        if (!File::exists($target)) {
            File::makeDirectory($target, 0755, true);
            $this->info('Created storage/app/public directory.');
        }

        // Remove existing link if it exists
        if (File::exists($link) || is_link($link)) {
            if (is_link($link)) {
                unlink($link);
            } else {
                File::deleteDirectory($link);
            }
            $this->info('Removed existing storage link.');
        }

        // Try to create symlink using PHP's symlink function
        if (function_exists('symlink')) {
            if (symlink($target, $link)) {
                $this->info('The [public/storage] link has been connected to [storage/app/public].');
                $this->info('The links have been created.');
                return Command::SUCCESS;
            } else {
                $this->error('Failed to create symbolic link using symlink() function.');
            }
        } else {
            $this->error('symlink() function is not available.');
        }

        // If symlink() doesn't work, provide manual instructions
        $this->warn('');
        $this->warn('Automatic symlink creation failed. Please create it manually:');
        $this->warn('');
        $this->warn('Option 1: Via SSH/Terminal:');
        $this->warn('  cd ' . base_path());
        $this->warn('  ln -s ' . $target . ' ' . $link);
        $this->warn('');
        $this->warn('Option 2: Via cPanel File Manager:');
        $this->warn('  1. Navigate to public directory');
        $this->warn('  2. Create a new symbolic link');
        $this->warn('  3. Link to: ' . $target);
        $this->warn('  4. Name it: storage');
        $this->warn('');
        $this->warn('Option 3: Contact your hosting provider to enable symlink() or exec() functions.');

        return Command::FAILURE;
    }
}
