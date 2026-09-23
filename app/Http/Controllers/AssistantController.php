<?php

namespace App\Http\Controllers;

use App\AI\WaggiesAssistant;
use App\Http\Requests\AssistantRequest;
use App\Support\ClinicalSafetyBoundary;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AssistantController extends Controller
{
    public function store(AssistantRequest $request, ClinicalSafetyBoundary $safetyBoundary): JsonResponse
    {
        if ($safeResponse = $safetyBoundary->responseFor($request->string('message')->toString())) {
            return response()->json(['message' => $safeResponse, 'sources' => []]);
        }

        $agent = WaggiesAssistant::make($request->validated('history', []));

        if (! $agent->isConfigured()) {
            return response()->json([
                'message' => 'The assistant is temporarily unavailable. Please contact Waggies directly for help.',
            ], 503);
        }

        try {
            $response = $agent->prompt(
                $request->string('message')->toString(),
                provider: $agent->provider(),
                model: $agent->model(),
            );

            return response()->json([
                'message' => $response->text,
                'sources' => $response->meta->citations
                    ->map(static fn (mixed $citation): array => method_exists($citation, 'toArray') ? $citation->toArray() : [])
                    ->filter(fn (array $citation): bool => $citation !== [])
                    ->values()
                    ->all(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Waggies assistant request failed.', ['exception' => $exception::class]);

            return response()->json([
                'message' => 'The assistant is temporarily unavailable. Please contact Waggies directly for help.',
            ], 503);
        }
    }
}
