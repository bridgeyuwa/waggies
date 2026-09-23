<?php

namespace App\Enums;

enum ClinicalPublicationStatus: string
{
    case Unpublished = 'unpublished';
    case Published = 'published';
}
