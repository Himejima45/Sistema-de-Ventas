<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ifsnop\Mysqldump\Mysqldump;
use ZipArchive;

class SafeDatabaseBackup extends Command
{
    protected $signature = 'db:backup-safe';
    protected $description = 'Backup database using pure PHP and compress to ZIP';

    public function handle()
    {
        $db = config('database.connections.mysql');

        // Generate SQL file path
        $timestamp = date('Y-m-d_H-i-s');
        $sqlPath = storage_path("app/backups/backup_{$timestamp}.sql");
        $zipPath = storage_path("app/backups/backup_{$timestamp}.sql.zip");

        // Ensure backup directory exists
        $backupDir = dirname($sqlPath);
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        try {
            // Step 1: Create SQL dump
            $dump = new Mysqldump(
                "mysql:host={$db['host']};dbname={$db['database']}",
                $db['username'],
                $db['password'],
                [],
                [
                    'add-drop-table' => true,
                    'complete-insert' => true,
                    'default-character-set' => 'utf8mb4',
                ]
            );

            $dump->start($sqlPath);
            $this->info("✅ SQL dump created: " . basename($sqlPath));

            // Step 2: Compress to ZIP
            if (!class_exists('ZipArchive')) {
                $this->warn("⚠️ ZipArchive not available. Skipping compression.");
                return 0;
            }

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                $zip->addFile($sqlPath, basename($sqlPath)); // Add file with name inside ZIP
                $zip->close();

                // Step 3: Delete the original .sql file (optional)
                unlink($sqlPath);

                $this->info("✅ Backup compressed to: " . basename($zipPath));
            } else {
                $this->error("❌ Failed to create ZIP archive.");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("Backup failed: " . $e->getMessage());
            return 1;
        }

        // Optional: Keep only last 7 ZIP backups
        $this->cleanupOldBackups($backupDir, 7);

        return 0;
    }

    /**
     * Keep only the latest N backup files.
     */
    protected function cleanupOldBackups(string $dir, int $keep = 7)
    {
        $files = glob($dir . '/backup_*.sql.zip');
        if (count($files) <= $keep) {
            return;
        }

        // Sort by modification time (newest first)
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // Delete older ones
        $toDelete = array_slice($files, $keep);
        foreach ($toDelete as $file) {
            unlink($file);
            $this->info("🗑️ Deleted old backup: " . basename($file));
        }
    }
}