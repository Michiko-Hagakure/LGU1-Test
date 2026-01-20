<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class BackupDownload extends Model
{
    protected $connection = 'auth_db';
    
    protected $fillable = [
        'backup_file',
        'otp_hash',
        'requested_by',
        'otp_expires_at',
        'downloaded',
        'downloaded_at',
        'ip_address',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'downloaded_at' => 'datetime',
        'downloaded' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function verifyOtp($otp)
    {
        return Hash::check($otp, $this->otp_hash);
    }

    public function isExpired()
    {
        return $this->otp_expires_at->isPast();
    }

    public function markAsDownloaded($ipAddress = null)
    {
        $this->update([
            'downloaded' => true,
            'downloaded_at' => now(),
            'ip_address' => $ipAddress,
        ]);
    }
}
