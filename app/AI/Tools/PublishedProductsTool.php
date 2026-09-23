<?php

namespace App\AI\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final class PublishedProductsTool implements Tool
{
    public function description(): string
    {
        return 'Return the public Waggies shop catalogue and current listed prices.';
    }

    public function handle(Request $request): string
    {
        return json_encode(Product::query()->published()->get(['name', 'description', 'category', 'price', 'currency', 'availability']), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
