<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'faqs';

    protected $fillable = [
        'category_id',
        'question',
        'answer',
        'sort_order',
        'is_published',
        'view_count',
        'helpful_count',
        'not_helpful_count',
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
    ];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
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
