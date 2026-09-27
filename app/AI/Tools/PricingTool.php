<?php

namespace App\AI\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final class PricingTool implements Tool
{
    public function description(): string
    {
        return 'Return public indicative service prices and explain that final availability and quotes must be confirmed by Waggies.';
    }

    public function handle(Request $request): string
    {
        $services = collect(config('waggies_pricing.services', []))->map(function (array $service): array {
            return [
                'label' => $service['label'] ?? null,
                'unit' => $service['unit'] ?? null,
                'pricing_mode' => $service['pricing_mode'] ?? null,
                'variants' => collect($service['variants'] ?? [])->map(function (array $variant): array {
                    return [
                        'label' => $variant['label'] ?? null,
                        'type' => $variant['type'] ?? null,
                        'request_only' => $variant['request_only'] ?? false,
                        'pricing_note' => $variant['pricing_note'] ?? null,
                        'amount' => $variant['amount'] ?? null,
                        'max_amount' => $variant['max_amount'] ?? null,
                        'size_rates' => collect($variant['size_rates'] ?? [])->map(fn (array $rate): array => [
                            'label' => $rate['label'] ?? null,
                            'guidance' => $rate['guidance'] ?? null,
                            'manual_review' => $rate['manual_review'] ?? false,
                            'amount' => $rate['amount'] ?? null,
                            'max_amount' => $rate['max_amount'] ?? null,
                        ])->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->all();

        return json_encode(['currency' => config('waggies_pricing.currency', 'NGN'), 'services' => $services], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
