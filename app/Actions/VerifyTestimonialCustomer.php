<?php

namespace App\Actions;

use App\Enums\SuiteCrmVerificationStatus;
use App\Integrations\SuiteCrm\SuiteCrmClient;
use App\Models\Testimonial;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

final class VerifyTestimonialCustomer
{
    public function __construct(private readonly SuiteCrmClient $suiteCrm) {}

    public function execute(Testimonial $testimonial): SuiteCrmVerificationStatus
    {
        try {
            $matches = $this->suiteCrm->findCustomers((string) $testimonial->contact_method, (string) $testimonial->contact_value);
        } catch (ConnectionException) {
            return $this->recordStatus($testimonial, SuiteCrmVerificationStatus::NetworkFailure);
        } catch (RequestException $exception) {
            return $this->recordStatus($testimonial, $exception->response->status() === 401
                ? SuiteCrmVerificationStatus::AuthenticationFailure
                : SuiteCrmVerificationStatus::ApiFailure);
        }

        if ($matches === []) {
            return $this->recordStatus($testimonial, SuiteCrmVerificationStatus::NotFound);
        }

        if (count($matches) !== 1) {
            return $this->recordStatus($testimonial, SuiteCrmVerificationStatus::AmbiguousMatch);
        }

        $match = $matches[0];
        $testimonial->forceFill([
            'crm_match_status' => SuiteCrmVerificationStatus::Matched->value,
            'suitecrm_record_id' => isset($match['id']) ? (string) $match['id'] : null,
        ])->save();

        return SuiteCrmVerificationStatus::Matched;
    }

    private function recordStatus(Testimonial $testimonial, SuiteCrmVerificationStatus $status): SuiteCrmVerificationStatus
    {
        $testimonial->forceFill([
            'crm_match_status' => $status->value,
            'suitecrm_record_id' => null,
        ])->save();

        return $status;
    }
}
