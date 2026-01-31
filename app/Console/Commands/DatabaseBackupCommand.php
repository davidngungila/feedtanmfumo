<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Models\Backup;
use Carbon\Carbon;
use ZipArchive;

class DatabaseBackupCommand extends Command
{
    protected $signature = 'backup:database {--encrypt : Encrypt the backup file}';
    protected $description = 'Create encrypted database backup and upload to off-site storage';

    public function handle(): int
    {
        $this->info('Starting database backup...');

        try {
            $database = config('database.connections.mysql.database');
            $host = config('database.connections.mysql.host');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            // Create backup directory if it doesn't exist
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Generate backup filename
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$database}_{$timestamp}.sql";
            $filepath = $backupDir.'/'.$filename;

            // Create MySQL dump
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Failed to create database dump');
            }

            // Compress the backup
            $zipFilename = str_replace('.sql', '.zip', $filename);
            $zipFilepath = $backupDir.'/'.$zipFilename;
            $zip = new ZipArchive();

            if ($zip->open($zipFilepath, ZipArchive::CREATE) !== true) {
                throw new \Exception('Failed to create zip archive');
            }

            $zip->addFile($filepath, $filename);
            $zip->close();

            // Delete original SQL file
            unlink($filepath);

            // Encrypt if requested
            $encrypted = false;
            if ($this->option('encrypt') || config('backup.encrypt', true)) {
                $encryptionKey = config('backup.encryption_key', config('app.key'));
                $zipContent = file_get_contents($zipFilepath);
                $encryptedContent = Crypt::encryptString($zipContent);
                
                $encryptedFilename = str_replace('.zip', '.enc.zip', $zipFilename);
                $encryptedFilepath = $backupDir.'/'.$encryptedFilename;
                file_put_contents($encryptedFilepath, $encryptedContent);
                
                // Delete unencrypted zip
                unlink($zipFilepath);
                
                $zipFilepath = $encryptedFilepath;
                $zipFilename = $encryptedFilename;
                $encrypted = true;
            }

            // Get file size
            $fileSize = filesize($zipFilepath);
            $fileSizeFormatted = $this->formatBytes($fileSize);

            // Upload to off-site storage (S3, FTP, etc.)
            $offsiteUploaded = $this->uploadToOffsite($zipFilepath, $zipFilename);

            // Create backup record
            $backup = Backup::create([
                'name' => $zipFilename,
                'type' => 'database',
                'file_path' => $zipFilepath,
                'file_size' => $fileSizeFormatted,
                'status' => 'completed',
                'created_by' => null, // System backup
                'completed_at' => now(),
            ]);

            // Clean up old backups (keep last 30 days)
            $this->cleanupOldBackups(30);

            $this->info("Backup created successfully: {$zipFilename}");
            $this->info("File size: {$fileSizeFormatted}");
            $this->info("Encrypted: ".($encrypted ? 'Yes' : 'No'));
            $this->info("Off-site upload: ".($offsiteUploaded ? 'Success' : 'Failed'));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Backup failed: '.$e->getMessage());

            // Create failed backup record
            Backup::create([
                'name' => 'backup_'.Carbon::now()->format('Y-m-d_H-i-s'),
                'type' => 'database',
                'file_path' => '',
                'file_size' => '0 MB',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'created_by' => null,
            ]);

            return Command::FAILURE;
        }
    }

    protected function uploadToOffsite(string $filepath, string $filename): bool
    {
        try {
            // Check if off-site storage is configured
            $offsiteDisk = config('backup.offsite_disk', 's3');
            
            if (!config("filesystems.disks.{$offsiteDisk}")) {
                $this->warn('Off-site storage not configured, skipping upload');
                return false;
            }

            // Upload to configured disk
            $remotePath = 'backups/'.date('Y/m').'/'.$filename;
            Storage::disk($offsiteDisk)->put($remotePath, file_get_contents($filepath));

            $this->info("Uploaded to off-site storage: {$remotePath}");
            return true;
        } catch (\Exception $e) {
            $this->warn('Off-site upload failed: '.$e->getMessage());
            return false;
        }
    }

    protected function cleanupOldBackups(int $daysToKeep): void
    {
        $cutoffDate = Carbon::now()->subDays($daysToKeep);
        
        // Delete old backup files
        $backupDir = storage_path('app/backups');
        $files = glob($backupDir.'/backup_*.{zip,enc.zip}', GLOB_BRACE);
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoffDate->timestamp) {
                unlink($file);
                $this->info("Deleted old backup: ".basename($file));
            }
        }

        // Delete old backup records
        Backup::where('created_at', '<', $cutoffDate)
            ->where('status', 'completed')
            ->delete();
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision).' '.$units[$i];
    }
}
