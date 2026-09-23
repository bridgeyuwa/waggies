<?php

namespace App\Models;

use App\Enums\MedicationClassification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Medication extends Model
{
    protected $fillable = ['generic_name', 'brand_name', 'manufacturer', 'active_ingredients', 'classification', 'species', 'indications', 'routes', 'warnings', 'contraindications', 'interactions', 'clinical_content_id'];

    protected function casts(): array
    {
        return ['classification' => MedicationClassification::class, 'active_ingredients' => 'array', 'species' => 'array', 'indications' => 'array', 'routes' => 'array', 'warnings' => 'array', 'contraindications' => 'array', 'interactions' => 'array'];
    }

    /**
     * @return BelongsTo<ClinicalContent, $this>
     */
    public function clinicalContent(): BelongsTo
    {
        return $this->belongsTo(ClinicalContent::class);
    }

    public function formulations(): HasMany
    {
        return $this->hasMany(MedicationFormulation::class);
    }

    public function jurisdictions(): HasMany
    {
        return $this->hasMany(MedicationJurisdiction::class);
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->whereIn('clinical_content_id', ClinicalContent::query()->public()->select('id'));
    }

    public function isPublic(): bool
    {
        return $this->clinicalContent()->first()?->isPubliclyEligible() ?? false;
    }
}
