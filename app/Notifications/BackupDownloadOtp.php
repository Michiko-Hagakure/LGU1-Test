<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BackupDownloadOtp extends Notification
{

    public $otp;
    public $backupFile;
    public $expiresAt;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp, $backupFile, $expiresAt)
    {
        $this->otp = $otp;
        $this->backupFile = $backupFile;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Database Backup Download - Password Required')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('You have requested to download a database backup file.')
                    ->line('**Backup File:** ' . $this->backupFile)
                    ->line('The backup file is password-protected for security. Use the OTP below to extract the backup:')
                    ->line('## **' . $this->otp . '**')
                    ->line('**Important Instructions:**')
                    ->line('1. Download the backup ZIP file from the admin panel')
                    ->line('2. When prompted, enter this OTP as the password to extract the backup')
                    ->line('3. This OTP will expire at: **' . $this->expiresAt->format('F d, Y h:i A') . '** (Philippine Time)')
                    ->line('⚠️ **Security Notice:** This OTP is valid for 15 minutes only. Do not share this password with anyone.')
                    ->salutation('Stay Secure,  
LGU1 System Security Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
            'backup_file' => $this->backupFile,
            'expires_at' => $this->expiresAt,
        ];
    }
}
