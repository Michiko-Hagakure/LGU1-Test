<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundRequest extends Model
{
    use HasFactory;

    protected $connection = 'facilities_db';

    protected $table = 'refund_requests';

    protected $fillable = [
        'booking_id',
        'user_id',
        'booking_reference',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'facility_name',
        'original_amount',
        'refund_percentage',
        'refund_amount',
        'refund_type',
        'reason',
        'refund_method',
        'account_name',
        'account_number',
        'bank_name',
        'status',
        'processed_by',
        'processed_at',
        'or_number',
        'treasurer_notes',
        'initiated_by',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'refund_percentage' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calculate refund amount based on cancellation policy.
     * Admin rejection = always 100%.
     * Citizen cancellation: 7+ days = 100%, 4-6 days = 50%, <3 days = 0%.
     */
    public static function calculateRefundPercentage(string $refundType, $eventDate): float
    {
        if ($refundType === 'admin_rejected') {
            return 100.00;
        }

        // Citizen cancellation policy
        $daysUntilEvent = now()->diffInDays(\Carbon\Carbon::parse($eventDate), false);

        if ($daysUntilEvent >= 7) {
            return 100.00;
        } elseif ($daysUntilEvent >= 4) {
            return 50.00;
        } else {
            return 0.00;
        }
    }
}
