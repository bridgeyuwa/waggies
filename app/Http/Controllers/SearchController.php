<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\SearchDocument;
use App\Support\SearchDocumentSynchronizer;
use Illuminate\Http\JsonResponse;

final class SearchController extends Controller
{
    public function __invoke(SearchRequest $request, SearchDocumentSynchronizer $synchronizer): JsonResponse
    {
        $query = $request->string('q')->toString();

        if ($query === '') {
            return $this->response($query, []);
        }

        if (! SearchDocument::query()->exists()) {
            $synchronizer->rebuild();
        }

        $results = SearchDocument::search($query)
            ->where('searchable', true)
            ->orderBy('boost', 'desc')
            ->orderBy('published_at', 'desc')
            ->take(8)
            ->query(static function ($query): void {
                $query->select(['id', 'source_type', 'source_key', 'title', 'excerpt', 'url', 'category', 'searchable']);
            })
            ->get()
            ->map(fn (SearchDocument $document): array => $document->toPublicSearchResult())
            ->values()
            ->all();

        return $this->response($query, $results);
    }

    /**
     * @param  array<int, array<string, mixed>>  $results
     */
    private function response(string $query, array $results): JsonResponse
    {
        return response()
            ->json(['query' => $query, 'results' => $results])
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
