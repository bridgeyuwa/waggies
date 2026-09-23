<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class MedicationJurisdiction extends Model
{
    protected $fillable = ['medication_id', 'jurisdiction', 'registration_number', 'registration_status', 'product_name', 'label_reference', 'approval_date', 'applicant', 'notes'];

    protected function casts(): array
    {
        return ['approval_date' => 'date'];
    }

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }
}
