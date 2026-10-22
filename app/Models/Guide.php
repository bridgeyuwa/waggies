<?php

namespace App\Models;

use App\Support\CountryCatalog;
use Carbon\Carbon;
use DateTimeInterface;
use Filament\Forms\Components\RichEditor\FileAttachmentProviders\SpatieMediaLibraryFileAttachmentProvider;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
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

    public const string RELOCATION_CATEGORY = 'Pet Relocation';

    public const string RELOCATION_DIRECTION_IMPORT = 'import';

    public const string RELOCATION_DIRECTION_EXPORT = 'export';

    public const string RELOCATION_SCOPE_STANDARD_EXPORT = 'standard_export';

    public const string RELOCATION_SCOPE_IMPORT_GROUP = 'import_group';

    public const string RELOCATION_SCOPE_IMPORT_COUNTRY = 'import_country';

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
        'relocation_direction',
        'relocation_scope',
        'route_label',
        'origin_country_code',
        'destination_country_code',
        'last_reviewed_at',
        'source_links',
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

            $guide->assertRelocationMetadataIsConsistent();

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
            'last_reviewed_at' => 'date',
            'source_links' => 'array',
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
        $categories = self::query()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category')
            ->unique()
            ->values()
            ->all();

        return collect([...$categories, self::RELOCATION_CATEGORY])
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function relocationDirectionOptions(): array
    {
        return [
            self::RELOCATION_DIRECTION_EXPORT => 'Nigeria → destination country',
            self::RELOCATION_DIRECTION_IMPORT => 'Origin country → Nigeria',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function relocationScopeOptions(): array
    {
        return [
            self::RELOCATION_SCOPE_STANDARD_EXPORT => 'Standard export from Nigeria',
            self::RELOCATION_SCOPE_IMPORT_GROUP => 'Grouped import to Nigeria',
            self::RELOCATION_SCOPE_IMPORT_COUNTRY => 'Single-country import to Nigeria',
        ];
    }

    /**
     * @return list<string>
     */
    public static function retiredPublicSlugs(): array
    {
        return [
            'grooming-services-explained',
            'pet-transport-what-to-know',
            ...array_keys(self::retiredPublicSlugRedirects()),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function retiredPublicSlugRedirects(): array
    {
        return [
            'moving-your-pet-from-nigeria-to-the-european-union' => 'requirements-for-exporting-your-pet-from-nigeria',
            'bringing-your-pet-from-the-european-union-to-nigeria' => 'requirements-for-importing-your-pet-to-nigeria-from-uk-eu-and-uae',
            'moving-your-pet-from-nigeria-to-kenya' => 'requirements-for-exporting-your-pet-from-nigeria',
            'bringing-your-pet-from-kenya-to-nigeria' => 'requirements-for-importing-your-pet-to-nigeria-from-kenya',
            'moving-your-pet-from-nigeria-to-the-united-kingdom' => 'requirements-for-exporting-your-pet-from-nigeria',
            'bringing-your-pet-from-the-united-kingdom-to-nigeria' => 'requirements-for-importing-your-pet-to-nigeria-from-uk-eu-and-uae',
            'moving-your-pet-from-nigeria-to-the-united-arab-emirates' => 'requirements-for-exporting-your-pet-from-nigeria',
            'bringing-your-pet-from-the-united-arab-emirates-to-nigeria' => 'requirements-for-importing-your-pet-to-nigeria-from-uk-eu-and-uae',
            'moving-your-pet-from-nigeria-to-the-united-states' => 'requirements-for-exporting-your-pet-from-nigeria',
            'bringing-your-pet-from-the-united-states-to-nigeria' => 'requirements-for-importing-your-pet-to-nigeria-from-the-united-states',
            'moving-your-pet-from-nigeria-to-south-africa' => 'requirements-for-exporting-your-pet-from-nigeria',
            'bringing-your-pet-from-south-africa-to-nigeria' => 'requirements-for-importing-your-pet-to-nigeria-from-south-africa',
        ];
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
            ->whereNotIn('slug', self::retiredPublicSlugs())
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
    public function toPublicArray(?string $fallbackImage = null): array
    {
        $cover = $this->getFirstMedia('cover');
        $countryCatalog = app(CountryCatalog::class);
        $directionOptions = self::relocationDirectionOptions();
        $lastReviewedAt = $this->getAttribute('last_reviewed_at');

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'category' => $this->category,
            'image' => $cover?->getUrl('detail') ?: ($fallbackImage ?? (string) $this->image),
            'imageSrcset' => $cover?->getSrcset('detail'),
            'imageAlt' => $this->image_alt ?: $this->title,
            'readTime' => $this->read_time,
            'content' => $this->content ?? '',
            'relocation' => [
                'isGuide' => $this->category === self::RELOCATION_CATEGORY,
                'direction' => $this->relocation_direction,
                'directionLabel' => $directionOptions[$this->relocation_direction] ?? null,
                'scope' => $this->relocation_scope,
                'scopeLabel' => self::relocationScopeOptions()[$this->relocation_scope] ?? null,
                'routeLabel' => $this->route_label,
                'originCountry' => $countryCatalog->label($this->origin_country_code),
                'destinationCountry' => $countryCatalog->label($this->destination_country_code),
                'lastReviewedAt' => $lastReviewedAt instanceof DateTimeInterface
                    ? $lastReviewedAt->format('F j, Y')
                    : null,
                'sourceLinks' => $this->publicSourceLinks(),
            ],
        ];
    }

    private function assertRelocationMetadataIsConsistent(): void
    {
        if (filled($this->relocation_scope)) {
            if (! array_key_exists($this->relocation_scope, self::relocationScopeOptions())) {
                throw new InvalidArgumentException("Invalid relocation guide scope [{$this->relocation_scope}].");
            }

            if (blank($this->relocation_direction) || blank($this->route_label)) {
                throw new InvalidArgumentException('Relocation guide scope, direction, and route label must be provided together.');
            }

            if ($this->relocation_scope === self::RELOCATION_SCOPE_STANDARD_EXPORT && $this->relocation_direction !== self::RELOCATION_DIRECTION_EXPORT) {
                throw new InvalidArgumentException('Standard export guides must use the export direction.');
            }

            if (in_array($this->relocation_scope, [self::RELOCATION_SCOPE_IMPORT_GROUP, self::RELOCATION_SCOPE_IMPORT_COUNTRY], true) && $this->relocation_direction !== self::RELOCATION_DIRECTION_IMPORT) {
                throw new InvalidArgumentException('Import guides must use the import direction.');
            }

            return;
        }

        $metadata = [
            $this->relocation_direction,
            $this->origin_country_code,
            $this->destination_country_code,
        ];
        $filledMetadata = array_values(array_filter(
            $metadata,
            static fn (mixed $value): bool => filled($value),
        ));

        if ($filledMetadata === []) {
            return;
        }

        if (count($filledMetadata) !== count($metadata)) {
            throw new InvalidArgumentException('Relocation direction, origin country, and destination country must be provided together.');
        }

        $direction = (string) $this->relocation_direction;
        $originCountryCode = strtoupper((string) $this->origin_country_code);
        $destinationCountryCode = strtoupper((string) $this->destination_country_code);

        if (! array_key_exists($direction, self::relocationDirectionOptions())) {
            throw new InvalidArgumentException("Invalid relocation direction [{$direction}].");
        }

        $countryCatalog = app(CountryCatalog::class);

        if ($countryCatalog->label($originCountryCode) === null || $countryCatalog->label($destinationCountryCode) === null) {
            throw new InvalidArgumentException('Relocation origin and destination must use valid country codes.');
        }

        if ($originCountryCode === $destinationCountryCode) {
            throw new InvalidArgumentException('Relocation origin and destination must be different countries.');
        }

        $fixedCountryCode = strtoupper((string) config('waggies_booking.relocation.fixed_country_code', 'NG'));

        if ($direction === self::RELOCATION_DIRECTION_EXPORT && $originCountryCode !== $fixedCountryCode) {
            throw new InvalidArgumentException('Export relocation guides must start from the configured Waggies country.');
        }

        if ($direction === self::RELOCATION_DIRECTION_IMPORT && $destinationCountryCode !== $fixedCountryCode) {
            throw new InvalidArgumentException('Import relocation guides must end in the configured Waggies country.');
        }

        $this->origin_country_code = $originCountryCode;
        $this->destination_country_code = $destinationCountryCode;
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    private function publicSourceLinks(): array
    {
        $links = [];
        $sourceLinks = $this->getAttribute('source_links');

        if (! is_array($sourceLinks)) {
            return [];
        }

        foreach ($sourceLinks as $sourceLink) {
            if (! is_array($sourceLink)) {
                continue;
            }

            $label = trim((string) ($sourceLink['label'] ?? ''));
            $url = Str::sanitizeUrl(trim((string) ($sourceLink['url'] ?? '')));
            $scheme = is_string($url) ? parse_url($url, PHP_URL_SCHEME) : null;

            if ($label === '' || ! is_string($url) || ! in_array($scheme, ['http', 'https'], true)) {
                continue;
            }

            $links[] = [
                'label' => $label,
                'url' => $url,
            ];
        }

        return $links;
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
