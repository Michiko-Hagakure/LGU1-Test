<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFavorite extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'facility_id',
        'favorited_at',
    ];

    protected $casts = [
        'favorited_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
