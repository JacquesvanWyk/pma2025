<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmailDomain implements ValidationRule
{
    /**
     * List of blocked disposable email domains
     */
    protected array $blockedDomains = [
        'checkyourform.xyz',
        'mailinator.com',
        'guerrillamail.com',
        '10minutemail.com',
        'tempmail.com',
        'throwaway.email',
        'trashmail.com',
        'maildrop.cc',
        'getnada.com',
        'temp-mail.org',
    ];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $domain = strtolower(substr(strrchr($value, '@'), 1));

        if (in_array($domain, $this->blockedDomains, true)) {
            $fail('Please provide a valid email address.');
        }
    }
}
