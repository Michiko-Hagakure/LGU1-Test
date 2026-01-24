<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $templates = DB::connection('mysql')
            ->table('message_templates')
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');
        
        $campaigns = DB::connection('mysql')
            ->table('notification_campaigns')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('staff.notifications.index', compact('templates', 'campaigns'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'notification_type' => 'required|in:email,sms,in-app',
            'recipient_type' => 'required|in:single,bulk',
            'recipients' => 'required',
            'template_id' => 'nullable|exists:message_templates,id',
            'subject' => 'required_if:notification_type,email',
            'message' => 'required|string',
            'schedule_type' => 'required|in:immediate,scheduled',
            'scheduled_at' => 'required_if:schedule_type,scheduled|nullable|date',
        ]);

        try {
            // Parse recipients
            $recipientsList = [];
            if ($request->recipient_type === 'single') {
                $recipientsList = [$request->recipients];
            } else {
                // Bulk: comma-separated or newline-separated
                $recipientsList = preg_split('/[\s,]+/', $request->recipients, -1, PREG_SPLIT_NO_EMPTY);
            }

            // Create notification campaign
            $campaignId = DB::connection('mysql')->table('notification_campaigns')->insertGetId([
                'type' => $request->notification_type,
                'recipients' => json_encode($recipientsList),
                'subject' => $request->subject,
                'message' => $request->message,
                'template_id' => $request->template_id,
                'status' => $request->schedule_type === 'immediate' ? 'sending' : 'pending',
                'scheduled_at' => $request->schedule_type === 'scheduled' ? $request->scheduled_at : now(),
                'sent_by' => session('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // If immediate, send now
            if ($request->schedule_type === 'immediate') {
                $this->processCampaign($campaignId);
            }

            return redirect()->route('staff.notifications.index')
                ->with('success', $request->schedule_type === 'immediate' 
                    ? 'Notification sent successfully to ' . count($recipientsList) . ' recipient(s)!' 
                    : 'Notification scheduled successfully!');
        } catch (\Exception $e) {
            \Log::error('Notification send failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send notification: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function processCampaign($campaignId)
    {
        $campaign = DB::connection('mysql')
            ->table('notification_campaigns')
            ->where('id', $campaignId)
            ->first();

        if (!$campaign) {
            return;
        }

        $recipients = json_decode($campaign->recipients, true);
        $sentCount = 0;
        $failedCount = 0;

        foreach ($recipients as $recipient) {
            try {
                if ($campaign->type === 'email') {
                    $this->sendEmail($recipient, $campaign->subject, $campaign->message);
                } elseif ($campaign->type === 'sms') {
                    $this->sendSms($recipient, $campaign->message);
                } elseif ($campaign->type === 'in-app') {
                    $this->sendInAppNotification($recipient, $campaign->message);
                }

                // Log success
                DB::connection('mysql')->table('notification_logs')->insert([
                    'campaign_id' => $campaignId,
                    'recipient' => $recipient,
                    'type' => $campaign->type,
                    'status' => 'sent',
                    'sent_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $sentCount++;
            } catch (\Exception $e) {
                // Log failure
                DB::connection('mysql')->table('notification_logs')->insert([
                    'campaign_id' => $campaignId,
                    'recipient' => $recipient,
                    'type' => $campaign->type,
                    'status' => 'failed',
                    'response' => $e->getMessage(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $failedCount++;
            }
        }

        // Update campaign status
        DB::connection('mysql')
            ->table('notification_campaigns')
            ->where('id', $campaignId)
            ->update([
                'status' => 'sent',
                'sent_at' => now(),
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
                'updated_at' => now(),
            ]);
    }

    private function sendEmail($email, $subject, $body)
    {
        \Mail::raw($body, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    private function sendSms($phone, $message)
    {
        $provider = DB::connection('auth_db')->table('system_settings')
            ->where('key', 'sms_provider')->value('value');
        $apiKey = DB::connection('auth_db')->table('system_settings')
            ->where('key', 'sms_api_key')->value('value');
        $senderName = DB::connection('auth_db')->table('system_settings')
            ->where('key', 'sms_sender_name')->value('value');

        if ($provider === 'semaphore') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.semaphore.co/api/v4/messages');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'apikey' => $apiKey,
                'number' => $phone,
                'message' => $message,
                'sendername' => $senderName,
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);
            if (isset($result['error'])) {
                throw new \Exception($result['message'] ?? 'SMS sending failed');
            }
        }
    }

    private function sendInAppNotification($userId, $message)
    {
        // Store in-app notification (using Laravel's notifications table)
        DB::connection('mysql')->table('notifications')->insert([
            'id' => \Str::uuid(),
            'type' => 'App\\Notifications\\StaffNotification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $userId,
            'data' => json_encode(['message' => $message]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function getTemplate($id)
    {
        $template = DB::connection('mysql')
            ->table('message_templates')
            ->where('id', $id)
            ->first();

        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        return response()->json([
            'subject' => $template->subject,
            'body' => $template->body,
            'type' => $template->type,
            'variables' => json_decode($template->variables, true),
        ]);
    }
}
