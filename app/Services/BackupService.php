<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Exception;

class BackupService
{
    protected string $backupDir;
    protected int $retentionDays;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
        $this->retentionDays = (int) env('BACKUP_RETENTION_DAYS', 14);

        if (!File::exists($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
        }
    }

    /**
     * Create a full database backup with pure PDO fallback (no mysqldump dependency).
     *
     * @param bool $includeFiles Whether to also backup storage/app/public directory
     * @return array
     */
    public function createBackup(bool $includeFiles = false): array
    {
        $timestamp = date('Y-m-d_His');
        $sqlFilename = "ps_crm_backup_{$timestamp}.sql";
        $sqlFilePath = $this->backupDir . DIRECTORY_SEPARATOR . $sqlFilename;

        $pdo = DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();

        $handle = fopen($sqlFilePath, 'w+');
        if (!$handle) {
            throw new Exception("Cannot create backup file at: {$sqlFilePath}");
        }

        // Header
        fwrite($handle, "-- Projukti Sheba CRM & Business Management System\n");
        fwrite($handle, "-- Database Backup: {$dbName}\n");
        fwrite($handle, "-- Generated At: " . date('Y-m-d H:i:s') . "\n");
        fwrite($handle, "-- --------------------------------------------------------\n\n");
        fwrite($handle, "SET NAMES utf8mb4;\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n");
        fwrite($handle, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n");

        $tables = [];
        $stmt = $pdo->query('SHOW TABLES');
        while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        $totalTables = count($tables);
        $totalRowsExported = 0;

        foreach ($tables as $table) {
            fwrite($handle, "--\n-- Table structure for table `{$table}`\n--\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");

            $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
            $createRow = $createStmt->fetch(\PDO::FETCH_NUM);
            if (isset($createRow[1])) {
                fwrite($handle, $createRow[1] . ";\n\n");
            }

            // Export rows in chunks
            $rowsStmt = $pdo->query("SELECT * FROM `{$table}`");
            $rows = $rowsStmt->fetchAll(\PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                fwrite($handle, "-- Dumping data for table `{$table}`\n");
                $columns = array_keys($rows[0]);
                $colNames = implode('`, `', array_map(function($c) { return addslashes($c); }, $columns));

                $batch = [];
                $batchCount = 0;
                foreach ($rows as $row) {
                    $vals = [];
                    foreach ($row as $val) {
                        if ($val === null) {
                            $vals[] = 'NULL';
                        } else {
                            $vals[] = $pdo->quote($val);
                        }
                    }
                    $batch[] = '(' . implode(', ', $vals) . ')';
                    $batchCount++;
                    $totalRowsExported++;

                    if ($batchCount >= 200) {
                        fwrite($handle, "INSERT INTO `{$table}` (`{$colNames}`) VALUES\n" . implode(",\n", $batch) . ";\n");
                        $batch = [];
                        $batchCount = 0;
                    }
                }

                if (!empty($batch)) {
                    fwrite($handle, "INSERT INTO `{$table}` (`{$colNames}`) VALUES\n" . implode(",\n", $batch) . ";\n");
                }
                fwrite($handle, "\n");
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
        fwrite($handle, "-- Backup Complete.\n");
        fclose($handle);

        $fileSize = filesize($sqlFilePath);

        // Optional: Compress into ZIP
        $zipFilename = "ps_crm_backup_{$timestamp}.zip";
        $zipFilePath = $this->backupDir . DIRECTORY_SEPARATOR . $zipFilename;
        $finalFile = $sqlFilename;
        $finalPath = $sqlFilePath;

        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                $zip->addFile($sqlFilePath, $sqlFilename);

                if ($includeFiles) {
                    $publicUploadsPath = storage_path('app/public');
                    if (File::exists($publicUploadsPath)) {
                        $files = File::allFiles($publicUploadsPath);
                        foreach ($files as $file) {
                            $relativeName = 'uploads/' . $file->getRelativePathname();
                            $zip->addFile($file->getRealPath(), $relativeName);
                        }
                    }
                }

                $zip->close();
                // Remove raw sql to save space, keeping zip
                @unlink($sqlFilePath);
                $finalFile = $zipFilename;
                $finalPath = $zipFilePath;
                $fileSize = filesize($zipFilePath);
            }
        }

        // Clean up old backups based on retention policy
        $prunedCount = $this->pruneOldBackups();

        Log::info("CRM Database Backup completed: {$finalFile} ({$fileSize} bytes)");

        return [
            'success' => true,
            'filename' => $finalFile,
            'filepath' => $finalPath,
            'size' => $this->formatBytes($fileSize),
            'size_bytes' => $fileSize,
            'tables_count' => $totalTables,
            'records_count' => $totalRowsExported,
            'pruned_count' => $prunedCount,
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get list of all available backups.
     */
    public function getBackupsList(): array
    {
        if (!File::exists($this->backupDir)) {
            return [];
        }

        $files = File::files($this->backupDir);
        $list = [];

        foreach ($files as $file) {
            $ext = strtolower($file->getExtension());
            if (in_array($ext, ['sql', 'zip', 'gz'])) {
                $list[] = [
                    'filename' => $file->getFilename(),
                    'filepath' => $file->getRealPath(),
                    'size' => $this->formatBytes($file->getSize()),
                    'size_bytes' => $file->getSize(),
                    'extension' => $ext,
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    'timestamp' => $file->getMTime(),
                ];
            }
        }

        // Sort latest first
        usort($list, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return $list;
    }

    /**
     * Delete backups older than retention days.
     */
    public function pruneOldBackups(): int
    {
        if (!File::exists($this->backupDir)) {
            return 0;
        }

        $files = File::files($this->backupDir);
        $thresholdTime = time() - ($this->retentionDays * 86400);
        $pruned = 0;

        foreach ($files as $file) {
            if ($file->getMTime() < $thresholdTime) {
                @unlink($file->getRealPath());
                $pruned++;
            }
        }

        return $pruned;
    }

    /**
     * Format bytes into readable format.
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
