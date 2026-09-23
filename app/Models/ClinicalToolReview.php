<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ClinicalToolReview extends Model
{
    protected $fillable = ['tool_key', 'clinical_status', 'risk_level', 'jurisdiction', 'version', 'clinical_source_id', 'reviewed_at', 'review_due_at', 'notes'];

    protected function casts(): array
    {
        return ['version' => 'integer', 'reviewed_at' => 'datetime', 'review_due_at' => 'datetime'];
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ClinicalSource::class, 'clinical_source_id');
    }
}
