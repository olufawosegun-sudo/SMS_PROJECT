<?php

namespace App\Http\Controllers;

use App\Models\DatabaseBackup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupController extends Controller
{
    /**
     * Display a listing of backups.
     */
    public function index()
    {
        // SECURITY: Only Owner can access
        if (Auth::user()->role->name !== 'Owner') {
            abort(403, 'Unauthorized. Only school owners can manage database backups.');
        }

        $school = Auth::user()->school;

        // Get all backups for this school
        $backups = DatabaseBackup::where('school_id', $school->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate storage usage
        $totalSize = DatabaseBackup::where('school_id', $school->id)->sum('backup_size');
        $backupCount = DatabaseBackup::where('school_id', $school->id)->count();

        $stats = [
            'total_backups' => $backupCount,
            'total_size' => $this->formatBytes($totalSize),
            'latest_backup' => DatabaseBackup::where('school_id', $school->id)
                ->orderBy('created_at', 'desc')
                ->first(),
        ];

        return view('database-backup.index', compact('backups', 'stats', 'school'));
    }

    /**
     * Create a new database backup.
     */
    public function create()
    {
        // SECURITY: Only Owner can access
        if (Auth::user()->role->name !== 'Owner') {
            abort(403, 'Unauthorized. Only school owners can create database backups.');
        }

        try {
            $school = Auth::user()->school;
            $user = Auth::user();

            // Generate backup filename
            $timestamp = Carbon::now()->format('Y-m-d_His');
            $filename = 'backup_'.$school->school_code.'_'.$timestamp.'.sql';
            $filepath = 'backups/'.$filename;

            // Get database credentials from config
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            // Debug: Log password value type and content
            \Log::info('Password debug - Value: '.var_export($password, true));
            \Log::info('Password debug - Type: '.gettype($password));
            \Log::info('Password debug - empty() check: '.(empty($password) ? 'true' : 'false'));
            \Log::info('Password debug - Is null: '.(is_null($password) ? 'true' : 'false'));
            \Log::info('Password debug - Length: '.strlen($password ?? ''));

            // Full path to backup file - use storage_path directly
            $backupDir = storage_path('app/backups');
            $fullPath = $backupDir.'/'.$filename;

            // Create backup directory if it doesn't exist (use filesystem directly to ensure correct path)
            if (! is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Try to find mysqldump in common XAMPP locations
            $mysqldumpPaths = [
                'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                'C:\\xampp\\mysql\\bin\\mysqldump',
                'mysqldump', // If in PATH
            ];

            $mysqldumpPath = null;
            foreach ($mysqldumpPaths as $path) {
                if (file_exists($path) || $path === 'mysqldump') {
                    $mysqldumpPath = $path;
                    break;
                }
            }

            if (! $mysqldumpPath) {
                throw new \Exception('mysqldump not found. Please ensure MySQL is installed.');
            }

            // Build mysqldump command for Windows
            // Use a temporary error file to capture stderr
            $errorFile = $backupDir.'/error_temp_'.uniqid().'.txt';

            // Conditionally include password only if it's not empty/null
            if (! empty($password) && trim($password) !== '') {
                \Log::info('Building command WITH password');
                $command = sprintf(
                    '"%s" --user=%s --password="%s" --host=%s --port=%s %s > "%s" 2> "%s"',
                    $mysqldumpPath,
                    $username,
                    $password,
                    $host,
                    $port,
                    $database,
                    $fullPath,
                    $errorFile
                );
            } else {
                \Log::info('Building command WITHOUT password (password is empty/null)');
                $command = sprintf(
                    '"%s" --user=%s --host=%s --port=%s %s > "%s" 2> "%s"',
                    $mysqldumpPath,
                    $username,
                    $host,
                    $port,
                    $database,
                    $fullPath,
                    $errorFile
                );
            }

            // Execute mysqldump command
            $output = [];
            $returnVar = null;
            exec($command, $output, $returnVar);

            // Read error output if it exists
            $errorOutput = '';
            if (file_exists($errorFile)) {
                $errorOutput = file_get_contents($errorFile);
                @unlink($errorFile); // Clean up temp error file
            }

            // Log the command and output for debugging
            \Log::info('Backup command: '.str_replace($password ?? '', '***', $command));
            \Log::info('Backup stdout: '.implode("\n", $output));
            \Log::info('Backup stderr: '.$errorOutput);
            \Log::info('Backup return code: '.$returnVar);

            if ($returnVar !== 0) {
                $errorMessage = 'Database backup failed. ';
                if (! empty($errorOutput)) {
                    $errorMessage .= 'Error: '.$errorOutput;
                } elseif (! empty($output)) {
                    $errorMessage .= 'Error: '.implode(' ', $output);
                } else {
                    $errorMessage .= 'mysqldump returned error code: '.$returnVar;
                }
                throw new \Exception($errorMessage);
            }

            // Check if file was created and get its size
            if (! file_exists($fullPath)) {
                throw new \Exception('Backup file was not created.');
            }

            $fileSize = filesize($fullPath);

            // Save backup record to database
            $backup = DatabaseBackup::create([
                'school_id' => $school->id,
                'backup_name' => $filename,
                'backup_path' => $filepath,
                'backup_size' => $fileSize,
                'status' => 'completed',
            ]);

            return redirect()->route('database-backup.index')
                ->with('success', 'Database backup created successfully! File: '.$filename.' ('.$this->formatBytes($fileSize).')');

        } catch (\Exception $e) {
            \Log::error('Database backup failed: '.$e->getMessage());
            \Log::error('Command that was executed: '.($command ?? 'Command not set'));
            \Log::error('mysqldump path used: '.($mysqldumpPath ?? 'Path not found'));

            return redirect()->route('database-backup.index')
                ->withErrors(['error' => 'Backup failed: '.$e->getMessage()]);
        }
    }

    /**
     * Download a backup file.
     */
    public function download($id)
    {
        // SECURITY: Only Owner can access
        if (Auth::user()->role->name !== 'Owner') {
            abort(403, 'Unauthorized. Only school owners can download database backups.');
        }

        $school = Auth::user()->school;

        // Find backup and verify it belongs to current school
        $backup = DatabaseBackup::where('school_id', $school->id)
            ->where('id', $id)
            ->firstOrFail();

        // Build full path to backup file
        $fullPath = storage_path('app/'.$backup->backup_path);

        // Check if file exists using full path
        if (! file_exists($fullPath)) {
            \Log::error('Backup file not found at: '.$fullPath);

            return redirect()->route('database-backup.index')
                ->withErrors(['error' => 'Backup file not found. Path: '.$backup->backup_path]);
        }

        // Download the file using response()->download()
        return response()->download($fullPath, $backup->backup_name);
    }

    /**
     * Delete a backup.
     */
    public function destroy($id)
    {
        // SECURITY: Only Owner can access
        if (Auth::user()->role->name !== 'Owner') {
            abort(403, 'Unauthorized. Only school owners can delete database backups.');
        }

        $school = Auth::user()->school;

        // Find backup and verify it belongs to current school
        $backup = DatabaseBackup::where('school_id', $school->id)
            ->where('id', $id)
            ->firstOrFail();

        try {
            // Build full path to backup file
            $fullPath = storage_path('app/'.$backup->backup_path);

            // Delete the physical file if it exists
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            // Delete the database record
            $backup->delete();

            return redirect()->route('database-backup.index')
                ->with('success', 'Backup deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Backup deletion failed: '.$e->getMessage());

            return redirect()->route('database-backup.index')
                ->withErrors(['error' => 'Failed to delete backup: '.$e->getMessage()]);
        }
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision).' '.$units[$i];
    }
}
