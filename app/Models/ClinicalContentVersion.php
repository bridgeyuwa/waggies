<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ClinicalContentVersion extends Model
{
    protected $fillable = ['clinical_content_id', 'version', 'snapshot', 'change_reason', 'created_by', 'reviewed_by', 'approved_at'];

    protected function casts(): array
    {
        return ['snapshot' => 'array', 'approved_at' => 'datetime'];
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(ClinicalContent::class, 'clinical_content_id');
    }
}
