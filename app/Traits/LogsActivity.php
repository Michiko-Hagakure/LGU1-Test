<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created', 'Created new record');
        });

        static::updated(function ($model) {
            $model->logActivity('updated', 'Updated record', $model->getChanges());
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', 'Deleted record');
        });
    }

    public function logActivity(string $event, string $description, array $properties = [])
    {
        $user = Auth::user();

        ActivityLog::create([
            'log_name' => $this->getLogName(),
            'description' => $description,
            'subject_id' => $this->id,
            'subject_type' => get_class($this),
            'event' => $event,
            'causer_id' => $user?->id,
            'causer_type' => $user ? get_class($user) : null,
            'properties' => array_merge([
                'attributes' => $this->attributesToArray(),
            ], $properties),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected function getLogName(): string
    {
        return strtolower(class_basename($this));
    }
}
