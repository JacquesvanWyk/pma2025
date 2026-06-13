<?php

namespace App\Services;

use Illuminate\Http\Request;

class PayFastService
{
    public function verifySignature(Request $request): bool
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

        return md5($pfParamString) === $request->input('signature');
    }

    public function buildFields(array $opts): array
    {
        $fields = array_merge([
            'merchant_id' => config('camp.payfast_merchant_id', '13157150'),
            'merchant_key' => env('PAYFAST_MERCHANT_KEY', ''),
        ], $opts);

        $fields['signature'] = $this->generateSignature($fields);

        return $fields;
    }

    private function generateSignature(array $data): string
    {
        ksort($data);
        $paramString = http_build_query($data);

        $passphrase = env('PAYFAST_PASSPHRASE', '');
        if (! empty($passphrase)) {
            $paramString .= '&passphrase='.urlencode($passphrase);
        }

        return md5($paramString);
    }
}
