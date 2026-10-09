<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:backup-db {--files : Include storage uploaded files in backup zip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform an automated full database and file backup for Projukti Sheba CRM';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService)
    {
        $this->info("🚀 Starting Projukti Sheba CRM Backup...");

        $includeFiles = $this->option('files') ?? false;

        try {
            $result = $backupService->createBackup($includeFiles);

            $this->info("✅ Backup successfully created!");
            $this->table(
                ['Metric', 'Value'],
                [
                    ['File Name', $result['filename']],
                    ['File Size', $result['size']],
                    ['Tables Exported', $result['tables_count']],
                    ['Rows Exported', $result['records_count']],
                    ['Old Backups Pruned', $result['pruned_count']],
                    ['Saved Location', $result['filepath']],
                    ['Timestamp', $result['created_at']],
                ]
            );

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("❌ Backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
