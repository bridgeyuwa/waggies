<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

final class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use HasUuids;
    use InteractsWithMedia;
    use Searchable;

    public const string STATUS_DRAFT = 'draft';

    public const string STATUS_PUBLISHED = 'published';

    public const string STATUS_ARCHIVED = 'archived';

    public const string AVAILABILITY_AVAILABLE = 'available';

    public const string AVAILABILITY_LIMITED = 'limited';

    public const string AVAILABILITY_UNAVAILABLE = 'unavailable';

    public const int MAX_GALLERY_IMAGES = 8;

    protected $attributes = [
        'currency' => 'NGN',
        'status' => self::STATUS_DRAFT,
        'features' => '[]',
        'is_indexable' => true,
        'include_in_sitemap' => true,
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'category',
        'image',
        'image_alt',
        'badge',
        'features',
        'status',
        'availability',
        'sort_order',
        'published_at',
        'seo_title',
        'seo_description',
        'is_indexable',
        'include_in_sitemap',
    ];

    protected static function booted(): void
    {
        self::saving(function (self $product): void {
            if (! in_array($product->status, array_keys(self::statusOptions()), true)) {
                throw new \InvalidArgumentException("Invalid Product status [{$product->status}].");
            }

            if ($product->status === self::STATUS_PUBLISHED && $product->published_at === null) {
                $product->published_at = now();
            }

            if ($product->status !== self::STATUS_PUBLISHED) {
                $product->published_at = null;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'price' => 'integer',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
            'availability' => 'string',
            'is_indexable' => 'boolean',
            'include_in_sitemap' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
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
     * Limit Products to published records that may be indexed publicly.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeIndexable(Builder $query): Builder
    {
        return $query->published()->where('is_indexable', true);
    }

    /**
     * Limit Products to records eligible for the public sitemap.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeSitemapEligible(Builder $query): Builder
    {
        return $query->indexable()->where('include_in_sitemap', true);
    }

    public function isIndexable(): bool
    {
        return (bool) $this->is_indexable && $this->isPublished();
    }

    public function isSitemapEligible(): bool
    {
        return $this->isIndexable() && (bool) $this->include_in_sitemap;
    }

    public function shouldBeSearchable(): bool
    {
        return $this->isIndexable();
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'features' => collect((array) $this->getAttribute('features'))->map(function (mixed $feature): string {
                return is_array($feature) ? (string) ($feature['label'] ?? '') : (string) $feature;
            })->filter()->implode(', '),
        ];
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
        return self::query()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category', 'category')
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function availabilityOptions(): array
    {
        return [
            self::AVAILABILITY_AVAILABLE => 'Available',
            self::AVAILABILITY_LIMITED => 'Limited availability',
            self::AVAILABILITY_UNAVAILABLE => 'Currently unavailable',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useDisk('public');

        $this->addMediaCollection('images')
            ->useDisk('public')
            ->withResponsiveImages();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $thumb = $this->addMediaConversion('thumb');
        $thumb->setManipulations(
            static function (Manipulations $manipulations): void {
                $manipulations->fit(Fit::Crop, 800, 800);
            }
        );
        $thumb->performOnCollections('image', 'images')->nonQueued();

        $detail = $this->addMediaConversion('detail');
        $detail->setManipulations(
            static function (Manipulations $manipulations): void {
                $manipulations->fit(Fit::Max, 1600, 1600);
            }
        );
        $detail->performOnCollections('image', 'images')->withResponsiveImages()->nonQueued();
    }

    public function publicImageUrl(string $conversion = 'detail'): string
    {
        return $this->getFirstMediaUrl('image', $conversion) ?: (string) $this->image;
    }

    /**
     * Adapt the persisted record to the existing public shop contract.
     *
     * @return array<string, mixed>
     */
    public function toPublicArray(?string $fallbackImage = null): array
    {
        $media = $this->getFirstMedia('image');
        $gallery = $this->getMedia('images');
        $primaryMedia = $gallery->first() ?? $media;
        $primaryImage = $primaryMedia?->getUrl('detail') ?? $this->publicImageUrl();

        if ($primaryImage === '') {
            $primaryImage = (string) $fallbackImage;
        }

        return [
            'id' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
            'category' => $this->category,
            'image' => $primaryImage,
            'imageSrcset' => $primaryMedia?->getSrcset('detail'),
            'alt' => $this->image_alt ?: $this->name,
            'badge' => $this->badge,
            'features' => $this->features ?? [],
            'availability' => $this->availability ?: self::AVAILABILITY_AVAILABLE,
            'availabilityLabel' => self::availabilityOptions()[$this->availability] ?? 'Availability to be confirmed',
            'gallery' => $gallery->map(fn (Media $item): array => [
                'url' => $item->getUrl('detail'),
                'thumb' => $item->getUrl('thumb'),
                'srcset' => $item->getSrcset('detail'),
                'alt' => $item->getCustomProperty('alt') ?: $this->image_alt ?: $this->name,
            ])->values()->all(),
        ];
    }
}
