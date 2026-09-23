<?php

namespace App\Enums;

enum SuiteCrmVerificationStatus: string
{
    case Matched = 'matched';
    case NotFound = 'not_found';
    case AuthenticationFailure = 'authentication_failure';
    case NetworkFailure = 'network_failure';
    case ApiFailure = 'api_failure';
    case AmbiguousMatch = 'ambiguous_match';
}
