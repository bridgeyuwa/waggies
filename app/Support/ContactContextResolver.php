<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Http\Request;

final class ContactContextResolver
{
    /**
     * @return array<string, mixed>
     */
    public function resolve(Request $request): array
    {
        $rawIntent = $this->value($request->query('intent'));
        $service = $this->value($request->query('service'));
        $variant = $this->value($request->query('variant'));
        $tier = $this->value($request->query('tier'));
        $resolvedService = $this->resolveService($service, $tier);
        $intent = $this->resolveIntent($rawIntent, $resolvedService);
        $productId = $this->value($request->query('product'));
        $productName = $this->value($request->query('productName'));
        $product = $productId !== null
            ? Product::query()->published()->where('slug', $productId)->first()
            : null;

        return [
            'rawIntent' => $rawIntent ?? '',
            'intent' => $intent,
            'service' => $service,
            'resolvedService' => $resolvedService,
            'variant' => $variant,
            'tier' => $tier,
            'productId' => $productId,
            'productName' => $product !== null ? $product->name : $productName,
            'source' => $this->allowedSource($this->value($request->query('source'))),
            'product' => $product ? ['name' => $product->name] : null,
        ];
    }

    private function resolveIntent(?string $raw, ?string $service): string
    {
        $map = [
            'service' => 'SERVICE_REQUEST', 'booking' => 'BOOKING_REQUEST', 'quote' => 'QUOTE_REQUEST',
            'veterinary' => 'VETERINARY_REQUEST', 'transport' => 'TRANSPORT_REQUEST', 'relocation' => 'RELOCATION_REQUEST',
            'product-inquiry' => 'PRODUCT_INQUIRY', 'cart-order' => 'CART_ORDER', 'contact' => 'CONTACT_REQUEST',
            'general' => 'GENERAL_INQUIRY', 'tool-assistance' => 'TOOL_ASSISTANCE', 'partnership' => 'PARTNERSHIP_REQUEST',
            'careers' => 'CAREERS_REQUEST', 'loyalty' => 'LOYALTY_REQUEST',
        ];

        if (in_array($raw, ['book', 'save', 'consult'], true)) {
            return match ($service) {
                'vet-care' => 'VETERINARY_REQUEST',
                'relocation' => 'QUOTE_REQUEST',
                'local-transport', 'transport' => 'TRANSPORT_REQUEST',
                'grooming', 'training' => 'SERVICE_REQUEST',
                default => 'BOOKING_REQUEST',
            };
        }

        return $map[$raw] ?? 'GENERAL_INQUIRY';
    }

    private function resolveService(?string $service, ?string $tier): ?string
    {
        if ($service === 'relocation' && $tier === 'local') {
            return 'local-transport';
        }

        $allowed = [
            'boarding', 'boarding-dogs', 'boarding-cats', 'boarding-exotic', 'grooming', 'vet-care', 'vet',
            'training', 'relocation', 'relocation-import', 'relocation-export', 'local-transport', 'transport',
        ];

        if (! in_array($service, $allowed, true)) {
            return null;
        }

        return ['vet' => 'vet-care', 'transport' => 'local-transport'][$service] ?? $service;
    }

    private function allowedSource(?string $value): string
    {
        return in_array($value, ['contact-direct', 'pricing', 'service-page', 'product-page', 'transport', 'partnership', 'careers', 'loyalty', 'tool'], true)
            ? $value
            : 'contact-direct';
    }

    private function value(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : mb_substr($value, 0, 255);
    }
}
