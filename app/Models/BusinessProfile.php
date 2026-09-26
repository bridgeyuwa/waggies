<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class BusinessProfile extends Model
{
    protected $fillable = [
        'business_name',
        'primary_email',
        'phone',
        'phone_international',
        'whatsapp_url',
        'address_street',
        'address_city',
        'address_postal_code',
        'address_state',
        'address_country',
        'map_url',
        'timezone',
        'instagram_url',
        'facebook_url',
        'x_url',
        'linkedin_url',
        'tiktok_url',
        'youtube_url',
    ];

    protected function casts(): array
    {
        return [];
    }

    public static function current(): self
    {
        return self::query()->firstOrFail();
    }

    /**
     * @return array<string, string|null>
     */
    public function socialLinks(): array
    {
        return collect([
            'instagram' => $this->instagram_url,
            'facebook' => $this->facebook_url,
            'x' => $this->x_url,
            'linkedin' => $this->linkedin_url,
            'tiktok' => $this->tiktok_url,
            'youtube' => $this->youtube_url,
        ])->filter(fn (?string $url): bool => filled($url))->all();
    }

    public function addressLine(): string
    {
        return collect([
            $this->address_street,
            $this->address_city,
            $this->address_postal_code,
            $this->address_state,
            $this->address_country,
        ])->filter()->implode(', ');
    }

    /**
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'name' => $this->business_name,
            'email' => $this->primary_email,
            'phone' => $this->phone,
            'phoneInternational' => $this->phone_international,
            'phoneHref' => 'tel:'.preg_replace('/\D+/', '', (string) ($this->phone_international ?: $this->phone)),
            'whatsapp' => $this->whatsapp_url,
            'address' => $this->addressLine(),
            'mapUrl' => $this->map_url,
            'timezone' => $this->timezone,
            'socials' => $this->socialLinks(),
        ];
    }
}
