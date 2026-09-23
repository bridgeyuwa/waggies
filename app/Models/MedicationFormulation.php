<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class MedicationFormulation extends Model
{
    protected $fillable = ['medication_id', 'formulation', 'route', 'strength', 'strength_unit', 'concentration', 'concentration_unit', 'combination_ingredients', 'dose_data', 'dose_display_enabled'];

    protected function casts(): array
    {
        return ['strength' => 'decimal:4', 'concentration' => 'decimal:4', 'combination_ingredients' => 'array', 'dose_data' => 'array', 'dose_display_enabled' => 'boolean'];
    }

    /**
     * @return BelongsTo<Medication, $this>
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    public function canDisplayDose(): bool
    {
        return $this->dose_display_enabled && $this->medication()->first()?->isPublic() === true && $this->dose_data !== null;
    }
}
