<?php

namespace App\Http\Controllers;

use App\Models\PledgeProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
        if (!$this->validatePayFast($request)) {
            Log::error('PayFast validation failed');
            return response('Invalid signature', 400);
        }

        $paymentStatus = $request->input('payment_status');
        
        // Only process completed payments
        if ($paymentStatus !== 'COMPLETE') {
            Log::info('Payment not complete', ['status' => $paymentStatus]);
            return response('OK');
        }

        // Extract donation details
        $donationData = [
            'amount' => floatval($request->input('amount_gross')),
            'donor_name' => trim($request->input('name_first', '') . ' ' . $request->input('name_last', '')),
            'donor_email' => $request->input('email_address'),
            'transaction_id' => $request->input('pf_payment_id'),
            'is_monthly' => $request->input('subscription_type') == '1',
            'item_name' => $request->input('item_name', 'Donation'),
            'timestamp' => now()->toIso8601String(),
        ];

        // Ping Clawdbot webhook on Tailscale
        try {
            $clawdbotUrl = env('CLAWDBOT_WEBHOOK_URL', 'http://100.96.236.55:3333/webhook/donation');
            
            Http::timeout(5)->post($clawdbotUrl, $donationData);
            
            Log::info('Donation forwarded to Clawdbot', $donationData);
        } catch (\Exception $e) {
            Log::error('Failed to notify Clawdbot: ' . $e->getMessage());
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
        if (!empty($passphrase)) {
            $pfParamString .= '&passphrase=' . urlencode($passphrase);
        }

        $signature = md5($pfParamString);
        
        return $signature === $request->input('signature');
    }
}
