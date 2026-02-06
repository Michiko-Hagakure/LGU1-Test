<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymongoService
{
    private $secretKey;
    private $publicKey;
    private $baseUrl = 'https://api.paymongo.com/v1';
    private $enabled;

    public function __construct()
    {
        $this->secretKey = config('payment.paymongo_secret_key');
        $this->publicKey = config('payment.paymongo_public_key');
        $this->enabled = config('payment.paymongo_enabled', false);
    }

    /**
     * Check if Paymongo is enabled and configured
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->secretKey) && !empty($this->publicKey);
    }

    /**
     * Create a checkout session for facility reservation payment
     *
     * @param object $paymentSlip Payment slip data
     * @param object $booking Booking data
     * @param string $successUrl URL to redirect after successful payment
     * @param string $cancelUrl URL to redirect if payment is cancelled
     * @return array Checkout session data
     */
    public function createCheckoutSession($paymentSlip, $booking, $successUrl, $cancelUrl): array
    {
        try {
            if (!$this->isEnabled()) {
                throw new Exception('Paymongo is not enabled or configured');
            }

            $description = "Facility Reservation - {$booking->facility_name}";
            $amountInCentavos = (int) round($paymentSlip->amount_due * 100);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->withOptions(['verify' => false]) // Disable SSL verification for local dev
                ->post("{$this->baseUrl}/checkout_sessions", [
                    'data' => [
                        'attributes' => [
                            'send_email_receipt' => true,
                            'show_description' => true,
                            'show_line_items' => true,
                            'success_url' => $successUrl,
                            'cancel_url' => $cancelUrl,
                            'line_items' => [
                                [
                                    'currency' => 'PHP',
                                    'amount' => $amountInCentavos,
                                    'name' => $description,
                                    'quantity' => 1,
                                    'description' => "Booking Reference: {$paymentSlip->slip_number}"
                                ]
                            ],
                            'payment_method_types' => ['qrph'],
                            'description' => $description,
                            'metadata' => [
                                'payment_slip_id' => (string) $paymentSlip->id,
                                'booking_id' => (string) $paymentSlip->booking_id,
                                'slip_number' => (string) $paymentSlip->slip_number,
                            ]
                        ]
                    ]
                ]);

            if (!$response->successful()) {
                $error = $response->json();
                Log::error('Paymongo checkout creation failed', ['error' => $error]);
                throw new Exception($error['errors'][0]['detail'] ?? 'Failed to create checkout session');
            }

            $data = $response->json();
            
            return [
                'success' => true,
                'checkout_session_id' => $data['data']['id'],
                'checkout_url' => $data['data']['attributes']['checkout_url'],
                'expires_at' => $data['data']['attributes']['expires_at'] ?? null,
            ];

        } catch (Exception $e) {
            Log::error('Paymongo createCheckoutSession error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Retrieve checkout session details
     *
     * @param string $checkoutSessionId
     * @return array
     */
    public function getCheckoutSession(string $checkoutSessionId): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/checkout_sessions/{$checkoutSessionId}");

            if (!$response->successful()) {
                throw new Exception('Failed to retrieve checkout session');
            }

            $data = $response->json();
            $attributes = $data['data']['attributes'];

            return [
                'success' => true,
                'id' => $data['data']['id'],
                'status' => $attributes['status'] ?? 'unknown',
                'payment_intent' => $attributes['payment_intent'] ?? null,
                'payments' => $attributes['payments'] ?? [],
                'metadata' => $attributes['metadata'] ?? [],
            ];

        } catch (Exception $e) {
            Log::error('Paymongo getCheckoutSession error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check if a checkout session payment was successful
     *
     * @param string $checkoutSessionId
     * @return bool
     */
    public function isPaymentSuccessful(string $checkoutSessionId): bool
    {
        $session = $this->getCheckoutSession($checkoutSessionId);
        
        if (!$session['success']) {
            return false;
        }

        // Check if payments array has successful payment
        if (!empty($session['payments'])) {
            foreach ($session['payments'] as $payment) {
                if (($payment['attributes']['status'] ?? '') === 'paid') {
                    return true;
                }
            }
        }

        // Alternative: check payment intent status
        if (!empty($session['payment_intent'])) {
            $intentStatus = $session['payment_intent']['attributes']['status'] ?? '';
            return $intentStatus === 'succeeded';
        }

        return false;
    }

    /**
     * Get payment details from checkout session
     *
     * @param string $checkoutSessionId
     * @return array|null
     */
    public function getPaymentDetails(string $checkoutSessionId): ?array
    {
        $session = $this->getCheckoutSession($checkoutSessionId);
        
        if (!$session['success'] || empty($session['payments'])) {
            return null;
        }

        $payment = $session['payments'][0] ?? null;
        if (!$payment) {
            return null;
        }

        $attributes = $payment['attributes'] ?? [];
        
        return [
            'payment_id' => $payment['id'] ?? null,
            'amount' => ($attributes['amount'] ?? 0) / 100, // Convert from centavos
            'currency' => $attributes['currency'] ?? 'PHP',
            'status' => $attributes['status'] ?? 'unknown',
            'paid_at' => $attributes['paid_at'] ?? null,
            'payment_method' => $attributes['source']['type'] ?? 'unknown',
            'reference_number' => $payment['id'] ?? null,
        ];
    }

    /**
     * Create a QR Ph payment using Payment Intent workflow
     *
     * @param object $paymentSlip
     * @param object $booking
     * @param string $customerEmail
     * @param string $customerName
     * @return array
     */
    public function createQRPayment($paymentSlip, $booking, $customerEmail, $customerName): array
    {
        try {
            if (!$this->isEnabled()) {
                throw new Exception('Paymongo is not enabled or configured');
            }

            $amountInCentavos = (int) round($paymentSlip->amount_due * 100);
            $description = "Facility Reservation - {$booking->facility_name}";

            // Step 1: Create Payment Intent with qrph
            $intentResponse = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/payment_intents", [
                    'data' => [
                        'attributes' => [
                            'amount' => $amountInCentavos,
                            'payment_method_allowed' => ['qrph'],
                            'payment_method_options' => [
                                'card' => ['request_three_d_secure' => 'any']
                            ],
                            'currency' => 'PHP',
                            'description' => $description,
                            'statement_descriptor' => 'LGU Facility',
                            'metadata' => [
                                'payment_slip_id' => $paymentSlip->id,
                                'slip_number' => $paymentSlip->slip_number,
                            ]
                        ]
                    ]
                ]);

            if (!$intentResponse->successful()) {
                $error = $intentResponse->json();
                Log::error('Paymongo create intent failed', ['error' => $error]);
                throw new Exception($error['errors'][0]['detail'] ?? 'Failed to create payment intent');
            }

            $intentData = $intentResponse->json();
            $paymentIntentId = $intentData['data']['id'];
            $clientKey = $intentData['data']['attributes']['client_key'];

            // Step 2: Create QR Ph Payment Method
            $methodResponse = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/payment_methods", [
                    'data' => [
                        'attributes' => [
                            'type' => 'qrph',
                            'billing' => [
                                'name' => $customerName,
                                'email' => $customerEmail,
                            ]
                        ]
                    ]
                ]);

            if (!$methodResponse->successful()) {
                $error = $methodResponse->json();
                Log::error('Paymongo create payment method failed', ['error' => $error]);
                throw new Exception($error['errors'][0]['detail'] ?? 'Failed to create payment method');
            }

            $methodData = $methodResponse->json();
            $paymentMethodId = $methodData['data']['id'];

            // Step 3: Attach payment method to intent
            $attachResponse = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/payment_intents/{$paymentIntentId}/attach", [
                    'data' => [
                        'attributes' => [
                            'payment_method' => $paymentMethodId,
                            'client_key' => $clientKey,
                            'return_url' => url('/citizen/payments/' . $paymentSlip->id . '/qr-success')
                        ]
                    ]
                ]);

            if (!$attachResponse->successful()) {
                $error = $attachResponse->json();
                Log::error('Paymongo attach failed', ['error' => $error]);
                throw new Exception($error['errors'][0]['detail'] ?? 'Failed to attach payment method');
            }

            $attachData = $attachResponse->json();
            $nextAction = $attachData['data']['attributes']['next_action'] ?? null;

            if (!$nextAction || $nextAction['type'] !== 'consume_qr') {
                throw new Exception('QR code not generated');
            }

            return [
                'success' => true,
                'payment_intent_id' => $paymentIntentId,
                'qr_image' => $nextAction['code']['image_url'] ?? null,
                'amount' => $amountInCentavos / 100,
                'expires_in' => 30, // QR expires in 30 minutes
            ];

        } catch (Exception $e) {
            Log::error('Paymongo createQRPayment error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check Payment Intent status
     *
     * @param string $paymentIntentId
     * @return array
     */
    public function getPaymentIntentStatus(string $paymentIntentId): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/payment_intents/{$paymentIntentId}");

            if (!$response->successful()) {
                throw new Exception('Failed to get payment intent');
            }

            $data = $response->json();
            $attributes = $data['data']['attributes'];

            return [
                'success' => true,
                'status' => $attributes['status'],
                'amount' => ($attributes['amount'] ?? 0) / 100,
                'payments' => $attributes['payments'] ?? [],
            ];

        } catch (Exception $e) {
            Log::error('Paymongo getPaymentIntentStatus error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
