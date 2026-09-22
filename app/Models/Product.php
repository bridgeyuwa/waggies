<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public const string STATUS_DRAFT = 'draft';

    public const string STATUS_PUBLISHED = 'published';

    public const string STATUS_ARCHIVED = 'archived';

    protected $attributes = [
        'currency' => 'NGN',
        'status' => self::STATUS_DRAFT,
        'features' => '[]',
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
        'sort_order',
        'published_at',
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
                $manipulations->fit(Fit::Crop, 800, 800);
            }
        );
        $thumb->performOnCollections('image')->nonQueued();

        $detail = $this->addMediaConversion('detail');
        $detail->setManipulations(
            static function (Manipulations $manipulations): void {
                $manipulations->fit(Fit::Max, 1600, 1600);
            }
        );
        $detail->performOnCollections('image')->withResponsiveImages()->nonQueued();
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
    public function toPublicArray(): array
    {
        $media = $this->getFirstMedia('image');

        return [
            'id' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
            'category' => $this->category,
            'image' => $this->publicImageUrl(),
            'imageSrcset' => $media?->getSrcset('detail'),
            'alt' => $this->image_alt ?: $this->name,
            'badge' => $this->badge,
            'features' => $this->features ?? [],
        ];
    }
}
