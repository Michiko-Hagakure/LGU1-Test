<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\PaymentSlip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CancelOverdueBookings extends Command
{
    protected $signature = 'bookings:cancel-overdue';
    protected $description = 'Cancel bookings with overdue payment deadlines';

    public function handle()
    {
        $this->info('Checking for overdue payment slips...');

        $overdueSlips = PaymentSlip::where('status', 'unpaid')
            ->where('payment_deadline', '<', Carbon::now())
            ->get();

        if ($overdueSlips->isEmpty()) {
            $this->info('No overdue payment slips found.');
            return 0;
        }

        $canceledCount = 0;

        foreach ($overdueSlips as $slip) {
            try {
                DB::beginTransaction();

                $booking = Booking::find($slip->booking_id);

                if ($booking && $booking->status !== 'canceled') {
                    $booking->update([
                        'status' => 'canceled',
                        'canceled_reason' => 'Payment deadline exceeded',
                        'canceled_at' => Carbon::now(),
                    ]);

                    $slip->update([
                        'status' => 'expired',
                    ]);

                    $canceledCount++;
                    $this->info("Canceled booking #{$booking->id} - Payment slip {$slip->slip_number}");
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to cancel booking #{$slip->booking_id}: " . $e->getMessage());
            }
        }

        $this->info("Total bookings canceled: {$canceledCount}");
        return 0;
    }
}
