<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $backups = [];
        
        try {
            $backupPath = config('backup.backup.name');
            
            if ($disk->exists($backupPath)) {
                $files = $disk->files($backupPath);
                
                foreach ($files as $file) {
                    if (str_ends_with($file, '.zip')) {
                        $backups[] = [
                            'path' => $file,
                            'name' => basename($file),
                            'size' => $this->formatBytes($disk->size($file)),
                            'size_bytes' => $disk->size($file),
                            'date' => \Carbon\Carbon::createFromTimestamp($disk->lastModified($file))
                                ->timezone('Asia/Manila')
                                ->format('F d, Y h:i A'),
                            'timestamp' => $disk->lastModified($file),
                        ];
                    }
                }
                
                usort($backups, function ($a, $b) {
                    return $b['timestamp'] - $a['timestamp'];
                });
            }
        } catch (\Exception $e) {
            Log::error('Backup listing error: ' . $e->getMessage());
        }

        return view('admin.backup.index', compact('backups'));
    }

    public function create(Request $request)
    {
        try {
            // Use shell_exec with cmd.exe to run artisan in proper Windows environment
            $artisanPath = base_path('artisan');
            $command = 'cd /d "' . base_path() . '" && php "' . $artisanPath . '" backup:run --only-db 2>&1';
            
            $output = shell_exec($command);
            
            // Check if backup actually succeeded
            if ($output === null || (!str_contains($output, 'Backup completed') && str_contains(strtolower($output ?? ''), 'failed'))) {
                Log::error('Backup creation failed', ['output' => $output]);
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Backup failed. Please check that MySQL is running and try again.');
            }
            
            Log::info('Manual backup created successfully', ['output' => $output]);
            
            return redirect()->route('admin.backup.index')
                ->with('success', 'Backup created successfully!');
        } catch (\Exception $e) {
            Log::error('Backup creation exception: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download(Request $request, $fileName)
    {
        try {
            $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
            $backupPath = config('backup.backup.name');
            $filePath = $backupPath . '/' . $fileName;

            if (!$disk->exists($filePath)) {
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Backup file not found.');
            }

            // Get user from session (custom session-based auth)
            $userId = session('user_id');
            
            if (!$userId) {
                Log::error('Download failed - No user_id in session', [
                    'session_id' => session()->getId(),
                    'session_keys' => array_keys(session()->all()),
                ]);
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Authentication error. Please log out and log back in.');
            }

            $user = \App\Models\User::find($userId);
            
            if (!$user) {
                Log::error('Download failed - User not found', [
                    'user_id' => $userId,
                ]);
                return redirect()->route('admin.backup.index')
                    ->with('error', 'User not found. Please log out and log back in.');
            }

            // Generate 6-digit OTP
            $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = now()->addMinutes(15);

            // Create download record with hashed OTP
            $download = \App\Models\BackupDownload::create([
                'backup_file' => $fileName,
                'otp_hash' => \Hash::make($otp),
                'requested_by' => $user->id,
                'otp_expires_at' => $expiresAt,
                'ip_address' => $request->ip(),
            ]);

            // Send OTP via email
            $user->notify(new \App\Notifications\BackupDownloadOtp($otp, $fileName, $expiresAt));

            // Create password-protected ZIP
            $originalPath = storage_path('app/private/' . $backupPath . '/' . $fileName);
            $protectedPath = storage_path('app/private/' . $backupPath . '/protected_' . $fileName);

            // Use ZipArchive to create password-protected backup
            $zip = new \ZipArchive();
            if ($zip->open($protectedPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                $zip->addFile($originalPath, $fileName);
                $zip->setPassword($otp);
                $zip->setEncryptionName($fileName, \ZipArchive::EM_AES_256);
                $zip->close();

                // Mark as downloaded
                $download->markAsDownloaded(request()->ip());

                // Download the protected file and delete it after
                return response()->download($protectedPath, 'protected_' . $fileName)->deleteFileAfterSend(true);
            } else {
                throw new \Exception('Failed to create password-protected backup.');
            }

        } catch (\Exception $e) {
            Log::error('Backup download failed: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Download failed: ' . $e->getMessage());
        }
    }

    public function destroy($fileName)
    {
        try {
            $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
            $backupPath = config('backup.backup.name');
            $filePath = $backupPath . '/' . $fileName;
            $trashPath = $backupPath . '/trash';

            if ($disk->exists($filePath)) {
                // Soft delete: move to trash folder instead of permanent deletion
                if (!$disk->exists($trashPath)) {
                    $disk->makeDirectory($trashPath);
                }
                
                $trashedFilePath = $trashPath . '/' . $fileName;
                $disk->move($filePath, $trashedFilePath);
                
                Log::info('Backup soft deleted (moved to trash)', ['file' => $fileName]);
                
                return redirect()->route('admin.backup.index')
                    ->with('success', 'Backup moved to trash successfully.');
            }

            return redirect()->route('admin.backup.index')
                ->with('error', 'Backup file not found.');
        } catch (\Exception $e) {
            Log::error('Backup deletion failed: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }

    public function clean()
    {
        try {
            Artisan::call('backup:clean');
            
            return redirect()->route('admin.backup.index')
                ->with('success', 'Old backups cleaned successfully based on retention policy.');
        } catch (\Exception $e) {
            Log::error('Backup cleanup failed: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Cleanup failed: ' . $e->getMessage());
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
