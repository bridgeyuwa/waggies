<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class JobOpening extends Model
{
    use HasFactory;
    use HasUuids;

    public const string STATUS_DRAFT = 'draft';

    public const string STATUS_OPEN = 'open';

    public const string STATUS_CLOSED = 'closed';

    public const string STATUS_ARCHIVED = 'archived';

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
        'sort_order' => 0,
    ];

    protected $fillable = [
        'title',
        'department',
        'location',
        'employment_type',
        'summary',
        'description',
        'requirements',
        'status',
        'published_at',
        'closing_date',
        'sort_order',
        'application_email',
        'application_url',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'closing_date' => 'date',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_OPEN)
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('closing_date')->orWhereDate('closing_date', '>=', today());
            });
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_OPEN => 'Open',
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_ARCHIVED => 'Archived',
        ];
    }
}
