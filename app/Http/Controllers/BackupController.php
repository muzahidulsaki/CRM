<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    protected BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display list of all database & file backups.
     */
    public function index()
    {
        $backups = $this->backupService->getBackupsList();
        return view('backups.index', compact('backups'));
    }

    /**
     * Trigger a new manual backup.
     */
    public function create(Request $request)
    {
        $includeFiles = $request->boolean('include_files', false);

        try {
            $result = $this->backupService->createBackup($includeFiles);
            return redirect()->route('backups.index')->with('success', "New backup completed successfully! ({$result['filename']} - {$result['size']})");
        } catch (\Throwable $e) {
            return redirect()->route('backups.index')->with('error', "Backup failed: " . $e->getMessage());
        }
    }

    /**
     * Download a specific backup file.
     */
    public function download(string $filename)
    {
        // Sanitize filename to prevent directory traversal
        $sanitized = basename($filename);
        $filePath = storage_path('app/backups' . DIRECTORY_SEPARATOR . $sanitized);

        if (!File::exists($filePath)) {
            abort(404, 'Backup file not found.');
        }

        return response()->download($filePath);
    }

    /**
     * Delete an existing backup file.
     */
    public function destroy(string $filename)
    {
        $sanitized = basename($filename);
        $filePath = storage_path('app/backups' . DIRECTORY_SEPARATOR . $sanitized);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->route('backups.index')->with('success', "Backup file deleted successfully.");
        }

        return redirect()->route('backups.index')->with('error', "File not found.");
    }
}
