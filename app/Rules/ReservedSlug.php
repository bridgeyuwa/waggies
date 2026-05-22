<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ReservedSlug implements ValidationRule
{
    /** @var list<string> */
    private const RESERVED = [
        'admin',
        'api',
        'blog',
        'guides',
        'knowledge-base',
        'contact',
        'faq',
        'shop',
        'services',
        'relocation',
        'about',
        'loyalty',
        'privacy-policy',
        'terms-of-service',
        'cookies-policy',
        'newsletter',
        'login',
        'register',
        'up',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (in_array(strtolower((string) $value), self::RESERVED, true)) {
            $fail('This slug is reserved and cannot be used.');
        }
    }
}
