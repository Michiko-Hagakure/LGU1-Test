<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HelpArticle extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'help_articles';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'video_url',
        'screenshots',
        'sort_order',
        'is_published',
        'view_count',
        'helpful_count',
        'not_helpful_count',
        'tags',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
        'view_count' => 'integer',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
        'screenshots' => 'array',
        'tags' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function markHelpful()
    {
        $this->increment('helpful_count');
    }

    public function markNotHelpful()
    {
        $this->increment('not_helpful_count');
    }
}
