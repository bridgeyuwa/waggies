<?php

namespace App\Integrations\SuiteCrm;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final class SuiteCrmClient
{
    private function request(): PendingRequest
    {
        return Http::baseUrl(rtrim((string) config('services.suitecrm.base_url'), '/'))
            ->acceptJson()
            ->timeout((int) config('services.suitecrm.timeout', 10))
            ->withToken((string) config('services.suitecrm.token'));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findCustomers(string $contactMethod, string $contactValue): array
    {
        if (blank(config('services.suitecrm.base_url')) || blank(config('services.suitecrm.token'))) {
            return [];
        }

        $response = $this->request()->get('/customers/search', [
            'field' => $contactMethod,
            'value' => $contactValue,
        ]);

        $response->throw();

        return $response->json('data', []);
    }
}
