<?php

namespace App\Http\Controllers;

use App\Models\CampTshirtOrder;
use App\Services\PayFastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CampPaymentController extends Controller
{
    public function __construct(private readonly PayFastService $payFast) {}

    public function notify(Request $request)
    {
        Log::info('Camp PayFast ITN received', $request->all());

        if (! $this->payFast->verifySignature($request)) {
            Log::error('Camp PayFast signature invalid');

            return response('Invalid signature', 400);
        }

        if ($request->input('payment_status') !== 'COMPLETE') {
            return response('OK');
        }

        $reference = $request->input('m_payment_id');
        $order = CampTshirtOrder::where('id', $reference)
            ->where('payment_status', 'pending')
            ->first();

        if (! $order) {
            Log::warning('Camp T-shirt order not found', ['reference' => $reference]);

            return response('OK');
        }

        $order->markPaid(
            $request->input('pf_payment_id'),
            $request->all()
        );

        Log::info('Camp T-shirt order marked paid', ['order_id' => $order->id]);

        return response('OK');
    }
}
