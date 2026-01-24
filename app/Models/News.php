<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'image_path',
        'is_published',
        'is_featured',
        'is_urgent',
        'view_count',
        'tags',
        'published_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
        'view_count' => 'integer',
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
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

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }
}
