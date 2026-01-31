<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FundRequest extends Model {
    protected $connection = 'auth_db';
    
    protected $table = 'fund_requests';

    // You MUST add these fields or Laravel will block the save!
    protected $fillable = [
        'requester_name',
        'user_id',
        'amount',
        'purpose',
        'logistics',
        'status',
        'feedback',
        'response_data',
        'seminar_info',
        'seminar_image',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}