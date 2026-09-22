<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class GalleryItem extends Model implements HasMedia
{
    use HasFactory;
    use HasUuids;
    use InteractsWithMedia;

    public const string STATUS_DRAFT = 'draft';

    public const string STATUS_PUBLISHED = 'published';

    public const string STATUS_ARCHIVED = 'archived';

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    protected $fillable = [
        'category',
        'image',
        'image_alt',
        'caption',
        'sort_order',
        'status',
        'published_at',
    ];

    protected static function booted(): void
    {
        self::saving(function (self $item): void {
            if (! in_array($item->status, array_keys(self::statusOptions()), true)) {
                throw new InvalidArgumentException("Invalid Gallery Item status [{$item->status}].");
            }

            if ($item->status === self::STATUS_PUBLISHED && $item->published_at === null) {
                $item->published_at = now();
            }

            if ($item->status !== self::STATUS_PUBLISHED) {
                $item->published_at = null;
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $thumb = $this->addMediaConversion('thumb');
        $thumb->setManipulations(
            static function (Manipulations $manipulations): void {
                $manipulations->fit(Fit::Crop, 800, 600);
            }
        );
        $thumb->performOnCollections('image')->nonQueued();

        $detail = $this->addMediaConversion('detail');
        $detail->setManipulations(
            static function (Manipulations $manipulations): void {
                $manipulations->fit(Fit::Max, 1600, 1200);
            }
        );
        $detail->performOnCollections('image')->withResponsiveImages()->nonQueued();
    }

    public function publicImageUrl(string $conversion = 'detail'): string
    {
        return $this->getFirstMediaUrl('image', $conversion) ?: (string) $this->image;
    }

    /**
     * Adapt the persisted record to the existing public gallery contract.
     *
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        $media = $this->getFirstMedia('image');

        return [
            'id' => $this->id,
            'src' => $this->publicImageUrl(),
            'srcset' => $media?->getSrcset('detail'),
            'alt' => $this->image_alt,
            'category' => $this->category,
            'groupTitle' => $this->caption ?: $this->category,
            'caption' => $this->caption,
        ];
    }
}
