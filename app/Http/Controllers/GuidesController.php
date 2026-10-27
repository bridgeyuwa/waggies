<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\GuideSlugHistory;
use App\Support\ArticleBodyProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class GuidesController extends Controller
{
    public function show(Request $request, string $slug): View|RedirectResponse
    {
        $guideRecord = Guide::query()
            ->with('media')
            ->published()
            ->where('slug', $slug)
            ->first();

        if ($guideRecord === null) {
            $replacementSlug = Guide::retiredPublicSlugRedirects()[$slug] ?? null;

            if (is_string($replacementSlug)) {
                $canonical = route('guides.show', ['slug' => $replacementSlug]);

                if ($request->getQueryString() !== null) {
                    $canonical .= '?'.$request->getQueryString();
                }

                return redirect()->to($canonical, 308);
            }

            $slugHistory = GuideSlugHistory::query()
                ->where('slug', $slug)
                ->first();

            if ($slugHistory !== null) {
                $guideRecord = Guide::query()
                    ->with('media')
                    ->published()
                    ->whereKey($slugHistory->guide_id)
                    ->first();
            }

            if ($guideRecord === null) {
                abort(404);
            }

            $canonical = route('guides.show', ['slug' => $guideRecord->slug]);

            if ($request->getQueryString() !== null) {
                $canonical .= '?'.$request->getQueryString();
            }

            return redirect()->to($canonical, 308);
        }
        $guide = $guideRecord->toPublicArray("/media/guides/{$guideRecord->slug}/cover.jpg");

        $articleBody = ArticleBodyProcessor::process($guide['content']);
        $isIndexable = $guideRecord->isIndexable();
        $canonical = $isIndexable ? route('guides.show', ['slug' => $guideRecord->slug]) : null;
        $title = $guideRecord->seo_title ?: $guideRecord->title.' - Waggies Pet Handbook - Waggies';
        $description = $guideRecord->seo_description ?: $guideRecord->excerpt;

        $metadata = [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $isIndexable ? ['index', 'follow'] : ['noindex', 'follow'],
            'ogTitle' => $guideRecord->seo_title ?: $guideRecord->title,
            'ogDescription' => $description,
            'ogImage' => $guide['image'],
            'ogType' => 'article',
        ];
        $schemas = [];

        if ($isIndexable) {
            $schemas[] = Schema::article()
                ->headline($guideRecord->title)
                ->description($guideRecord->excerpt)
                ->url($canonical)
                ->publisher(Schema::organization()->name('Waggies')->url(route('home')))
                ->image($guide['image'])
                ->toArray();
        }
        $this->setPageHead($metadata, $schemas);

        return view('pages.guides.show', $metadata + [
            'navSection' => 'resources',
            'guide' => $guide,
            'headings' => $articleBody['headings'],
            'processedContent' => $articleBody['content'],
        ]);
    }
}
