<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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
        if (! Schema::hasTable('business_profiles')) {
            return self::fromConfig();
        }

        return self::query()->first() ?? self::fromConfig();
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
            'phoneHref' => 'tel:'.preg_replace('/\D+/', '', (string) $this->phone_international),
            'whatsapp' => $this->whatsapp_url,
            'address' => $this->addressLine(),
            'mapUrl' => $this->map_url ?? config('waggies.map_url'),
            'timezone' => $this->timezone,
            'socials' => $this->socialLinks(),
        ];
    }

    private static function fromConfig(): self
    {
        $address = config('waggies.address', []);
        $socials = config('waggies.socials', []);

        return new self([
            'business_name' => 'Waggies',
            'primary_email' => 'hello@waggies.ng',
            'phone' => config('waggies.phone'),
            'phone_international' => config('waggies.phone_international'),
            'whatsapp_url' => config('waggies.whatsapp'),
            'map_url' => config('waggies.map_url'),
            'address_street' => $address['street'] ?? null,
            'address_city' => $address['city'] ?? null,
            'address_postal_code' => $address['postal_code'] ?? null,
            'address_state' => $address['state'] ?? null,
            'address_country' => $address['country'] ?? null,
            'timezone' => 'Africa/Lagos',
            'instagram_url' => $socials['instagram'] ?? null,
            'facebook_url' => $socials['facebook'] ?? null,
            'x_url' => $socials['x'] ?? null,
            'linkedin_url' => $socials['linkedin'] ?? null,
            'tiktok_url' => $socials['tiktok'] ?? null,
            'youtube_url' => $socials['youtube'] ?? null,
        ]);
    }
}
