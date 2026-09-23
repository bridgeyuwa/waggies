<?php

namespace App\AI\Tools;

use App\Models\BusinessHour;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final class BusinessHoursTool implements Tool
{
    public function description(): string
    {
        return 'Return the current public Waggies opening-hours schedule.';
    }

    public function handle(Request $request): string
    {
        return json_encode(BusinessHour::publicSchedule(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
