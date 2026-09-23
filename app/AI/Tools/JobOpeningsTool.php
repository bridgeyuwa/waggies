<?php

namespace App\AI\Tools;

use App\Models\JobOpening;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final class JobOpeningsTool implements Tool
{
    public function description(): string
    {
        return 'Return currently open public Waggies job openings only.';
    }

    public function handle(Request $request): string
    {
        return json_encode(JobOpening::query()->open()->get(['title', 'department', 'location', 'employment_type', 'summary']), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
