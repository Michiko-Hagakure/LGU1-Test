<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'events';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'category',
        'image_path',
        'event_date',
        'event_time',
        'location',
        'organizer',
        'max_attendees',
        'is_published',
        'is_featured',
        'view_count',
        'tags',
        'published_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }
}
