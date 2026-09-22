<?php

namespace App\Models;

use Carbon\Carbon;
use Filament\Forms\Components\RichEditor\FileAttachmentProviders\SpatieMediaLibraryFileAttachmentProvider;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

final class Guide extends Model implements HasMedia, HasRichContent
{
    use HasSlug;
    use HasUuids;
    use InteractsWithMedia;
    use InteractsWithRichContent;

    public const string STATUS_DRAFT = 'draft';

    public const string STATUS_PUBLISHED = 'published';

    public const string STATUS_ARCHIVED = 'archived';

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
        'is_indexable' => true,
        'include_in_sitemap' => true,
    ];

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'category',
        'image',
        'image_alt',
        'read_time',
        'content',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'is_indexable',
        'include_in_sitemap',
    ];

    protected static function booted(): void
    {
        self::saving(function (self $guide): void {
            if (! in_array($guide->status, array_keys(self::statusOptions()), true)) {
                throw new InvalidArgumentException("Invalid Guide status [{$guide->status}].");
            }

            if ($guide->status === self::STATUS_PUBLISHED && $guide->published_at === null) {
                $guide->published_at = now();
            }

            if ($guide->status !== self::STATUS_PUBLISHED) {
                $guide->published_at = null;
            }
        });

        self::creating(function (self $guide): void {
            $guide->assertSlugIsNotOwnedByAnotherGuide();
        });

        self::updating(function (self $guide): void {
            $guide->assertSlugIsNotOwnedByAnotherGuide();
        });

        self::updated(function (self $guide): void {
            if ($guide->wasChanged('slug')) {
                GuideSlugHistory::firstOrCreate([
                    'slug' => $guide->getOriginal('slug'),
                ], [
                    'guide_id' => $guide->getKey(),
                    'created_at' => now(),
                ]);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_indexable' => 'boolean',
            'include_in_sitemap' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function slugHistories(): HasMany
    {
        return $this->hasMany(GuideSlugHistory::class);
    }

    public function setUpRichContent(): void
    {
        $this->registerRichContent('content')
            ->fileAttachmentProvider(
                SpatieMediaLibraryFileAttachmentProvider::make()
                    ->collection('content-attachments')
                    ->customProperties(['source' => 'rich-editor']),
            );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->useDisk('public');

        $this->addMediaCollection('content-attachments')
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $card = $this->addMediaConversion('card');
        $card->setManipulations(
            static function (Manipulations $manipulations): void {
                $manipulations->fit(Fit::Crop, 800, 500);
            }
        );
        $card->performOnCollections('cover')->nonQueued();

        $detail = $this->addMediaConversion('detail');
        $detail->setManipulations(
            static function (Manipulations $manipulations): void {
                $manipulations->fit(Fit::Crop, 1600, 1000);
            }
        );
        $detail->performOnCollections('cover')->withResponsiveImages()->nonQueued();
    }

    public function publicImageUrl(string $conversion = 'detail'): string
    {
        return $this->getFirstMediaUrl('cover', $conversion) ?: (string) $this->image;
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
     * @return array<int, string>
     */
    public static function categoryOptions(): array
    {
        return self::query()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Limit Guides to records that may render through the normal public route.
     *
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

    /**
     * Limit Guides to records that may participate in public search.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeIndexable(Builder $query): Builder
    {
        return $query->published()->where('is_indexable', true);
    }

    /**
     * Limit Guides to records eligible for the public sitemap.
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
        return $this->is_indexable && $this->isPublished();
    }

    public function isPublished(): bool
    {
        $publishedAt = $this->published_at;

        return $this->status === self::STATUS_PUBLISHED
            && ($publishedAt === null || Carbon::parse($publishedAt)->isPast());
    }

    public function isSitemapEligible(): bool
    {
        return $this->isIndexable() && $this->include_in_sitemap;
    }

    /**
     * Adapt the persisted record to the existing fixed Blade Guide contract.
     *
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        $cover = $this->getFirstMedia('cover');

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'category' => $this->category,
            'image' => $cover?->getUrl('detail') ?: (string) $this->image,
            'imageSrcset' => $cover?->getSrcset('detail'),
            'imageAlt' => $this->image_alt ?: $this->title,
            'readTime' => $this->read_time,
            'content' => $this->content ?? '',
        ];
    }

    private function assertSlugIsNotOwnedByAnotherGuide(): void
    {
        if (blank($this->slug)) {
            return;
        }

        $historyQuery = GuideSlugHistory::query()->where('slug', $this->slug);

        if ($this->exists) {
            $historyQuery->where('guide_id', '!=', $this->getKey());
        }

        if ($historyQuery->exists()) {
            throw new InvalidArgumentException("The Guide slug [{$this->slug}] is reserved by a previous Guide URL.");
        }
    }
}
