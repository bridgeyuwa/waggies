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
                'tiers' => collect($service['tiers'] ?? collect($service['variants'] ?? [])->flatMap(fn (array $variant): array => $variant['tiers'] ?? []))->map(fn (array $tier): array => [
                    'label' => $tier['label'] ?? null,
                    'type' => $tier['type'] ?? null,
                    'amount' => $tier['amount'] ?? null,
                    'max_amount' => $tier['max_amount'] ?? null,
                ])->values()->all(),
            ];
        })->all();

        return json_encode(['currency' => config('waggies_pricing.currency', 'NGN'), 'services' => $services], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
