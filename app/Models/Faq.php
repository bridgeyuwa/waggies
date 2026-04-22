<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    /** @use HasFactory<\Database\Factories\FaqFactory> */
    use HasFactory;

    protected $fillable = ['category', 'subcategory', 'question', 'answer', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function scopeForSubcategory(Builder $query, ?string $subcategory): Builder
    {
        if ($subcategory === null) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($subcategory) {
            $q->whereNull('subcategory')->orWhere('subcategory', $subcategory);
        });
    }

    public function getCategoryLabelAttribute(): string
    {
        return ucwords(str_replace('-', ' ', $this->category));
    }
}
