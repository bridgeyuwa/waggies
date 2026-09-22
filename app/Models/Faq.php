<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class Faq extends Model
{
    use HasFactory;
    use HasUuids;

    public const string STATUS_DRAFT = 'draft';

    public const string STATUS_PUBLISHED = 'published';

    public const string STATUS_ARCHIVED = 'archived';

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    protected $fillable = [
        'category',
        'subcategory',
        'question',
        'answer',
        'sort_order',
        'status',
        'published_at',
    ];

    protected static function booted(): void
    {
        self::saving(function (self $faq): void {
            if (! in_array($faq->status, array_keys(self::statusOptions()), true)) {
                throw new InvalidArgumentException("Invalid FAQ status [{$faq->status}].");
            }

            if ($faq->status === self::STATUS_PUBLISHED && $faq->published_at === null) {
                $faq->published_at = now();
            }

            if ($faq->status !== self::STATUS_PUBLISHED) {
                $faq->published_at = null;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function isPublished(): bool
    {
        $publishedAt = $this->published_at;

        return $this->status === self::STATUS_PUBLISHED
            && ($publishedAt === null || Carbon::parse($publishedAt)->isPast());
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_ARCHIVED => 'Archived',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function categoryOptions(): array
    {
        return [
            'boarding' => 'Boarding',
            'grooming' => 'Grooming',
            'vet-care' => 'Veterinary Care',
            'training' => 'Training',
            'relocation' => 'Pet Relocation',
            'transport' => 'Local Transport',
            'general' => 'General',
            'services' => 'Services',
        ];
    }

    /**
     * Adapt the persisted record to the existing public FAQ contract.
     *
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'question' => $this->question,
            'answer' => $this->answer,
        ];
    }
}
