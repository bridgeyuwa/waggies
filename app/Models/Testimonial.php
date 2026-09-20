<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    public const string STATUS_PENDING = 'pending';

    public const string STATUS_APPROVED = 'approved';

    public const string STATUS_REJECTED = 'rejected';

    public const string STATUS_ARCHIVED = 'archived';

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    protected $fillable = [
        'rating',
        'service',
        'title',
        'story',
        'author_name',
        'author_location',
        'pet_name',
        'pet_type',
        'photo_path',
        'consented_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'consented_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Limit testimonials to records approved for public publication.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
}
