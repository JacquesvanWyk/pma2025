<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\PledgeProgress;
use App\Notifications\DonationThankYouNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class DonateController extends Controller
{
    public function index()
    {
        return view('donate.index');
    }

    public function once()
    {
        return view('donate.once');
    }

    public function thankYou(Request $request)
    {
        return view('donate.thank-you', [
            'reference' => $request->query('ref'),
        ]);
    }

    public function monthly()
    {
        return view('donate.monthly');
    }

    public function pledge()
    {
        $pledgeProgress = PledgeProgress::current();

        return view('donate.pledge', [
            'currentAmount' => $pledgeProgress ? $pledgeProgress->current_amount : 0,
            'month' => $pledgeProgress ? $pledgeProgress->month : now()->format('F'),
            'goalAmount' => $pledgeProgress ? $pledgeProgress->goal_amount : 35000,
            'percentage' => $pledgeProgress ? $pledgeProgress->percentage : 0,
        ]);
    }

    public function notify(Request $request)
    {
        Log::info('PayFast ITN received', $request->all());

        // Validate PayFast signature
        if (! $this->validatePayFast($request)) {
            Log::error('PayFast validation failed');

            return response('Invalid signature', 400);
        }

        $paymentStatus = $request->input('payment_status');

        // Only process completed payments
        if ($paymentStatus !== 'COMPLETE') {
            Log::info('Payment not complete', ['status' => $paymentStatus]);

            return response('OK');
        }

        // Store donation in database
        $donation = Donation::create([
            'gateway' => 'payfast',
            'transaction_reference' => $request->input('pf_payment_id'),
            'amount' => floatval($request->input('amount_gross')),
            'currency' => 'ZAR',
            'donor_email' => $request->input('email_address'),
            'donor_name' => trim($request->input('name_first', '').' '.$request->input('name_last', '')),
            'status' => 'successful',
            'is_recurring' => $request->input('subscription_type') == '1',
            'item_name' => $request->input('item_name', 'Donation'),
            'metadata' => $request->all(),
        ]);

        Log::info('PayFast donation stored', ['id' => $donation->id]);

        // Send thank you email
        if ($donation->donor_email) {
            Notification::route('mail', $donation->donor_email)
                ->notify(new DonationThankYouNotification($donation));
        }

        // Ping Clawdbot webhook on Tailscale
        try {
            $clawdbotUrl = env('CLAWDBOT_WEBHOOK_URL', 'http://100.96.236.55:3333/webhook/donation');

            Http::timeout(5)->post($clawdbotUrl, [
                'amount' => $donation->amount,
                'donor_name' => $donation->donor_name,
                'donor_email' => $donation->donor_email,
                'transaction_id' => $donation->transaction_reference,
                'is_monthly' => $donation->is_recurring,
                'item_name' => $donation->item_name,
                'gateway' => 'payfast',
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify Clawdbot: '.$e->getMessage());
        }

        return response('OK');
    }

    private function validatePayFast(Request $request)
    {
        $pfData = [];
        foreach ($request->all() as $key => $val) {
            if ($key !== 'signature') {
                $pfData[$key] = $val;
            }
        }

        ksort($pfData);
        $pfParamString = http_build_query($pfData);

        $passphrase = env('PAYFAST_PASSPHRASE', '');
        if (! empty($passphrase)) {
            $pfParamString .= '&passphrase='.urlencode($passphrase);
        }

        $signature = md5($pfParamString);

        return $signature === $request->input('signature');
    }

    public function createPayPalPlan(Request $request)
    {
        $amount = floatval($request->input('amount'));

        if ($amount < 10) {
            return response()->json(['error' => 'Minimum amount is R10'], 400);
        }

        $clientId = env('PAYPAL_CLIENT_ID');
        $secret = env('PAYPAL_SECRET');
        $mode = env('PAYPAL_MODE', 'live');
        $baseUrl = $mode === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';

        // Get access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl.'/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId.':'.$secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        $response = curl_exec($ch);
        curl_close($ch);

        $tokenData = json_decode($response, true);

        if (! isset($tokenData['access_token'])) {
            Log::error('PayPal token fetch failed', ['response' => $response]);

            return response()->json(['error' => 'Failed to connect to PayPal'], 500);
        }

        $accessToken = $tokenData['access_token'];

        // Create product
        $productData = [
            'name' => 'PMA Monthly Donation',
            'description' => 'Monthly donation to Pioneer Missions Africa',
            'type' => 'SERVICE',
            'category' => 'CHARITY',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl.'/v1/catalogs/products');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$accessToken,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($productData));
        $response = curl_exec($ch);
        curl_close($ch);

        $productResponse = json_decode($response, true);

        if (! isset($productResponse['id'])) {
            Log::error('PayPal product creation failed', ['response' => $response]);

            return response()->json(['error' => 'Failed to create product'], 500);
        }

        $productId = $productResponse['id'];

        // Convert ZAR to USD (approximate rate: 18 ZAR = 1 USD)
        $amountUSD = number_format($amount / 18, 2, '.', '');

        // Create plan
        $planData = [
            'product_id' => $productId,
            'name' => 'Monthly Donation - R'.$amount,
            'description' => 'Monthly donation of R'.$amount.' (~$'.$amountUSD.') to Pioneer Missions Africa',
            'status' => 'ACTIVE',
            'billing_cycles' => [
                [
                    'frequency' => [
                        'interval_unit' => 'MONTH',
                        'interval_count' => 1,
                    ],
                    'tenure_type' => 'REGULAR',
                    'sequence' => 1,
                    'total_cycles' => 0, // Infinite
                    'pricing_scheme' => [
                        'fixed_price' => [
                            'value' => $amountUSD,
                            'currency_code' => 'USD',
                        ],
                    ],
                ],
            ],
            'payment_preferences' => [
                'auto_bill_outstanding' => true,
                'setup_fee_failure_action' => 'CONTINUE',
                'payment_failure_threshold' => 3,
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl.'/v1/billing/plans');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$accessToken,
            'Prefer: return=representation',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($planData));
        $response = curl_exec($ch);
        curl_close($ch);

        $planResponse = json_decode($response, true);

        if (! isset($planResponse['id'])) {
            Log::error('PayPal plan creation failed', ['response' => $response]);

            return response()->json(['error' => 'Failed to create plan'], 500);
        }

        return response()->json(['plan_id' => $planResponse['id']]);
    }

    public function paypalWebhook(Request $request)
    {
        Log::info('PayPal webhook received', $request->all());

        $eventType = $request->input('event_type');

        if ($eventType === 'PAYMENT.CAPTURE.COMPLETED' || $eventType === 'PAYMENT.SALE.COMPLETED') {
            $paymentData = $request->input('resource');

            // Store donation in database
            $donation = Donation::create([
                'gateway' => 'paypal',
                'transaction_reference' => $paymentData['id'],
                'amount' => floatval($paymentData['amount']['value']),
                'currency' => $paymentData['amount']['currency_code'] ?? 'USD',
                'donor_email' => $paymentData['payer']['email_address'] ?? null,
                'donor_name' => isset($paymentData['payer']['name'])
                    ? $paymentData['payer']['name']['given_name'].' '.$paymentData['payer']['name']['surname']
                    : null,
                'status' => 'successful',
                'is_recurring' => $eventType === 'BILLING.SUBSCRIPTION.ACTIVATED',
                'item_name' => 'Donation',
                'metadata' => $request->all(),
            ]);

            Log::info('PayPal donation stored', ['id' => $donation->id]);

            // Send thank you email
            if ($donation->donor_email) {
                Notification::route('mail', $donation->donor_email)
                    ->notify(new DonationThankYouNotification($donation));
            }

            try {
                $clawdbotUrl = env('CLAWDBOT_WEBHOOK_URL', 'http://100.96.236.55:3333/webhook/donation');

                Http::timeout(5)->post($clawdbotUrl, [
                    'amount' => $donation->amount,
                    'donor_name' => $donation->donor_name,
                    'donor_email' => $donation->donor_email,
                    'transaction_id' => $donation->transaction_reference,
                    'is_monthly' => $donation->is_recurring,
                    'item_name' => $donation->item_name,
                    'gateway' => 'paypal',
                    'timestamp' => now()->toIso8601String(),
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to notify Clawdbot: '.$e->getMessage());
            }
        }

        return response('OK');
    }

    public function paystackWebhook(Request $request)
    {
        Log::info('Paystack webhook received', $request->all());

        // Verify webhook signature
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();
        $secret = env('PAYSTACK_SECRET_KEY');

        if ($secret && $signature !== hash_hmac('sha512', $payload, $secret)) {
            Log::error('Paystack signature verification failed');

            return response('Invalid signature', 400);
        }

        $event = $request->input('event');
        $data = $request->input('data');

        if ($event === 'charge.success') {
            // Store donation in database
            $donation = Donation::create([
                'gateway' => 'paystack',
                'transaction_reference' => $data['reference'],
                'amount' => $data['amount'] / 100, // Paystack sends amount in kobo
                'currency' => $data['currency'] ?? 'ZAR',
                'donor_email' => $data['customer']['email'] ?? null,
                'donor_name' => $data['customer']['first_name'] ?? null,
                'status' => 'successful',
                'is_recurring' => false,
                'item_name' => $data['metadata']['custom_fields'][0]['value'] ?? 'Donation',
                'metadata' => $request->all(),
            ]);

            Log::info('Paystack donation stored', ['id' => $donation->id]);

            // Send thank you email
            if ($donation->donor_email) {
                Notification::route('mail', $donation->donor_email)
                    ->notify(new DonationThankYouNotification($donation));
            }

            try {
                $clawdbotUrl = env('CLAWDBOT_WEBHOOK_URL', 'http://100.96.236.55:3333/webhook/donation');

                Http::timeout(5)->post($clawdbotUrl, [
                    'amount' => $donation->amount,
                    'donor_name' => $donation->donor_name,
                    'donor_email' => $donation->donor_email,
                    'transaction_id' => $donation->transaction_reference,
                    'is_monthly' => $donation->is_recurring,
                    'item_name' => $donation->item_name,
                    'gateway' => 'paystack',
                    'timestamp' => now()->toIso8601String(),
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to notify Clawdbot: '.$e->getMessage());
            }
        }

        return response('OK');
    }

    public function getExchangeRate(Request $request)
    {
        // Check if we have a cached rate (valid for 1 hour)
        $cachedRate = cache('zar_usd_rate');
        $cachedTime = cache('zar_usd_rate_time');

        if ($cachedRate && $cachedTime && now()->diffInMinutes($cachedTime) < 60) {
            return response()->json([
                'rate' => $cachedRate,
                'cached' => true,
                'time' => $cachedTime->toIso8601String(),
            ]);
        }

        // Fetch live rate from ExchangeRate-API (free)
        try {
            $response = Http::timeout(5)->get('https://api.exchangerate-api.com/v4/latest/USD');

            if ($response->successful()) {
                $data = $response->json();
                $rate = $data['rates']['ZAR'] ?? null;

                if ($rate) {
                    // Cache the rate for 1 hour
                    cache(['zar_usd_rate' => $rate, 'zar_usd_rate_time' => now()], 3600);

                    return response()->json([
                        'rate' => $rate,
                        'cached' => false,
                        'time' => now()->toIso8601String(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange rate: '.$e->getMessage());
        }

        // Fallback to default rate if API fails
        $defaultRate = 18.0;
        cache(['zar_usd_rate' => $defaultRate, 'zar_usd_rate_time' => now()], 3600);

        return response()->json([
            'rate' => $defaultRate,
            'cached' => false,
            'fallback' => true,
            'time' => now()->toIso8601String(),
        ]);
    }
}
