<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactInquiry extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'contact_inquiries';

    protected $fillable = [
        'ticket_number',
        'user_id',
        'name',
        'email',
        'phone',
        'category',
        'subject',
        'message',
        'attachments',
        'status',
        'priority',
        'assigned_to',
        'staff_notes',
        'resolution',
        'assigned_at',
        'resolved_at',
        'closed_at',
        'deleted_by',
    ];

    protected $casts = [
        'attachments' => 'array',
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inquiry) {
            if (empty($inquiry->ticket_number)) {
                $inquiry->ticket_number = 'TKT-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function assignTo($userId)
    {
        $this->update([
            'assigned_to' => $userId,
            'assigned_at' => now(),
            'status' => 'open',
        ]);
    }

    public function markResolved($resolution)
    {
        $this->update([
            'status' => 'resolved',
            'resolution' => $resolution,
            'resolved_at' => now(),
        ]);
    }

    public function close()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }
}
