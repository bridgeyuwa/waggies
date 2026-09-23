<?php

namespace App\Enums;

enum ClinicalSourceType: string
{
    case Regulatory = 'regulatory';
    case ProductLabel = 'product_label';
    case ClinicalGuideline = 'clinical_guideline';
    case ProfessionalGuideline = 'professional_guideline';
    case TextbookReference = 'textbook/reference';
    case SystematicReview = 'systematic_review';
    case PeerReviewedStudy = 'peer_reviewed_study';
    case SpecialistResource = 'specialist_resource';
    case WaggiesClinicalMaterial = 'waggies_clinical_material';
}
