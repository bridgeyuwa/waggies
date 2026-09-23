<?php

namespace App\Http\Controllers;

use App\AI\WaggiesAssistant;
use App\Http\Requests\AssistantRequest;
use App\Support\ClinicalSafetyBoundary;
use Symfony\Component\HttpFoundation\Response;

final class AssistantStreamController extends Controller
{
    public function store(AssistantRequest $request, ClinicalSafetyBoundary $safetyBoundary): Response
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

        return $agent->stream(
            $request->string('message')->toString(),
            provider: $agent->provider(),
            model: $agent->model(),
        )->toResponse($request);
    }
}
