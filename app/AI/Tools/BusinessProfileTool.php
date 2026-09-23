<?php

namespace App\AI\Tools;

use App\Models\BusinessProfile;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final class BusinessProfileTool implements Tool
{
    public function description(): string
    {
        return 'Return public Waggies contact, address, WhatsApp, and social details.';
    }

    public function handle(Request $request): string
    {
        return json_encode(BusinessProfile::current()->toPublicArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
