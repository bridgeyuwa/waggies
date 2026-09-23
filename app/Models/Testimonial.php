<?php

namespace App\Models;

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

final class Testimonial extends Model implements HasMedia
{
    use HasFactory;
    use HasUuids;
    use InteractsWithMedia;

    public const string STATUS_PENDING = 'pending';

    public const string STATUS_APPROVED = 'approved';

    public const string STATUS_REJECTED = 'rejected';

    public const string STATUS_ARCHIVED = 'archived';

    public const string CRM_MATCHED = 'matched';

    public const string CRM_NOT_FOUND = 'not_found';

    public const string CRM_NOT_CHECKED = 'not_checked';

    public const string VERIFICATION_VERIFIED = 'verified';

    public const string VERIFICATION_NOT_VERIFIED = 'not_verified';

    public const string VERIFICATION_UNABLE = 'unable_to_verify';

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
        'contact_method',
        'contact_value',
        'crm_match_status',
        'identity_verification_status',
        'customer_relationship_status',
        'suitecrm_record_id',
        'verification_method',
        'verified_at',
        'verified_by',
        'verification_notes',
        'moderated_at',
        'moderated_by',
        'pet_name',
        'pet_type',
        'photo_path',
        'consented_at',
        'status',
        'published_at',
        'sort_order',
    ];

    protected static function booted(): void
    {
        self::saving(function (self $testimonial): void {
            if (! in_array($testimonial->status, array_keys(self::statusOptions()), true)) {
                throw new InvalidArgumentException("Invalid testimonial status [{$testimonial->status}].");
            }

            if ($testimonial->status === self::STATUS_APPROVED && $testimonial->eligibleForPublication() && $testimonial->published_at === null) {
                $testimonial->published_at = now();
            }

            if ($testimonial->status !== self::STATUS_APPROVED || ! $testimonial->eligibleForPublication()) {
                $testimonial->published_at = null;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'consented_at' => 'datetime',
            'published_at' => 'datetime',
            'verified_at' => 'datetime',
            'moderated_at' => 'datetime',
        ];
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_APPROVED)
            ->where('crm_match_status', self::CRM_MATCHED)
            ->where('identity_verification_status', self::VERIFICATION_VERIFIED)
            ->where('customer_relationship_status', self::VERIFICATION_VERIFIED)
            ->whereNotNull('consented_at')
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function eligibleForPublication(): bool
    {
        return $this->status === self::STATUS_APPROVED
            && $this->crm_match_status === self::CRM_MATCHED
            && $this->identity_verification_status === self::VERIFICATION_VERIFIED
            && $this->customer_relationship_status === self::VERIFICATION_VERIFIED
            && $this->consented_at !== null;
    }

    /**
     * @return array<string, string>
     */
    public static function verificationOptions(): array
    {
        return [
            self::VERIFICATION_NOT_VERIFIED => 'Not verified',
            self::VERIFICATION_VERIFIED => 'Verified',
            self::VERIFICATION_UNABLE => 'Unable to verify',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function crmMatchOptions(): array
    {
        return [
            self::CRM_MATCHED => 'CRM customer match',
            self::CRM_NOT_FOUND => 'CRM customer not found',
            'ambiguous_match' => 'Ambiguous match',
            'authentication_failure' => 'CRM authentication failure',
            'network_failure' => 'CRM network failure',
            'api_failure' => 'CRM API failure',
            self::CRM_NOT_CHECKED => 'Not checked',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending review',
            self::STATUS_APPROVED => 'Published',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_ARCHIVED => 'Archived',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function serviceOptions(): array
    {
        return [
            'boarding-dogs' => 'Dog Boarding',
            'boarding-cats' => 'Cat Boarding',
            'boarding-exotic' => 'Exotic Pet Boarding',
            'grooming' => 'Grooming',
            'vet-care' => 'Veterinary Care',
            'training' => 'Training',
            'relocation-import' => 'Pet Import',
            'relocation-export' => 'Pet Export',
            'local-transport' => 'Local Transport',
            'general' => 'General',
        ];
    }

    public static function normalizeService(string $service): string
    {
        return [
            'Boarding' => 'boarding-dogs',
            'Grooming' => 'grooming',
            'Vet Care' => 'vet-care',
            'Training' => 'training',
            'Transport' => 'local-transport',
            'Relocation' => 'relocation-import',
        ][$service] ?? $service;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile()
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(static function (Manipulations $manipulations): void {
                $manipulations->fit(Fit::Crop, 160, 160);
            })
            ->performOnCollections('photo')
            ->nonQueued();
    }

    public function publicPhotoUrl(): ?string
    {
        return $this->getFirstMediaUrl('photo', 'thumb') ?: null;
    }

    /**
     * Adapt the persisted record to the existing public testimonial contract.
     *
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'stars' => $this->rating,
            'quote' => $this->story,
            'authorInitial' => mb_substr($this->author_name, 0, 1),
            'authorName' => $this->author_name,
            'authorSubtitle' => $this->author_location,
            'service' => $this->service,
            'photo' => $this->publicPhotoUrl(),
        ];
    }

    /**
     * Adapt the record to the home-page testimonial spotlight contract.
     *
     * @return array<string, mixed>
     */
    public function toHomeArray(): array
    {
        return [
            'service' => self::serviceOptions()[$this->service] ?? $this->service,
            'quote' => $this->story,
            'initial' => mb_substr($this->author_name, 0, 1),
            'name' => $this->author_name,
            'subtitle' => $this->author_location,
        ];
    }
}
