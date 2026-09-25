<?php

namespace App\Providers;

use App\Models\BusinessHour;
use App\Models\BusinessProfile;
use App\Models\Faq;
use App\Models\Guide;
use App\Models\JobOpening;
use App\Models\KnowledgeArticle;
use App\Models\Product;
use App\Models\Testimonial;
use App\Observers\SearchContentObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Events\DiagnosingHealth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Head\Facades\Head;
use Laravel\Head\HeadBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(DiagnosingHealth::class, static function (): void {
            app(DatabaseManager::class)->connection()->getPdo();

            foreach ([storage_path(), storage_path('framework'), base_path('bootstrap/cache')] as $path) {
                if (! is_dir($path) || ! is_writable($path)) {
                    throw new \RuntimeException("Health check failed for writable path [{$path}].");
                }
            }
        });

        RateLimiter::for('contact-enquiries', static function (Request $request): Limit {
            return Limit::perMinute(5)->by($request->ip() ?: 'unknown');
        });

        RateLimiter::for('booking-requests', static function (Request $request): Limit {
            return Limit::perMinute(5)->by(Str::lower((string) $request->input('email')).'|'.($request->ip() ?: 'unknown'));
        });

        RateLimiter::for('newsletter-subscriptions', static function (Request $request): Limit {
            return Limit::perMinute(5)->by(Str::lower((string) $request->input('email')).'|'.($request->ip() ?: 'unknown'));
        });

        RateLimiter::for('testimonials', static function (Request $request): Limit {
            return Limit::perMinute(3)->by($request->ip() ?: 'unknown');
        });

        RateLimiter::for('search', static function (Request $request): Limit {
            return Limit::perMinute(60)->by($request->ip() ?: 'unknown');
        });

        RateLimiter::for('ai-assistant', static function (Request $request): Limit {
            return Limit::perMinute(10)->by($request->ip() ?: 'unknown');
        });

        foreach ([Guide::class, KnowledgeArticle::class, Product::class, Faq::class, JobOpening::class] as $model) {
            $model::observe(SearchContentObserver::class);
        }

        View::composer('components.waggies.proof-band', static function (\Illuminate\View\View $view): void {
            $serviceOptions = Testimonial::serviceOptions();
            $hrefs = [
                'boarding-dogs' => route('services.boarding.species', ['species' => 'dogs']),
                'boarding-cats' => route('services.boarding.species', ['species' => 'cats']),
                'boarding-exotic' => route('services.boarding.species', ['species' => 'exotic']),
                'grooming' => route('services.grooming'),
                'vet-care' => route('services.vet-care'),
                'training' => route('services.training'),
                'relocation-import' => route('relocation.import'),
                'relocation-export' => route('relocation.export'),
                'local-transport' => route('relocation.transport'),
                'general' => route('about.testimonials'),
            ];
            $variant = $view->getData()['variant'] ?? 'overview';
            $serviceFilter = match ($variant) {
                'relocation' => ['relocation-import', 'relocation-export', 'local-transport'],
                'boarding' => ['boarding-dogs', 'boarding-cats', 'boarding-exotic'],
                'boarding-dogs' => ['boarding-dogs'],
                'boarding-cats' => ['boarding-cats'],
                'boarding-exotic' => ['boarding-exotic'],
                default => ['boarding-dogs', 'grooming', 'relocation-import'],
            };

            $testimonials = Testimonial::query()
                ->published()
                ->whereIn('service', $serviceFilter)
                ->orderBy('sort_order')
                ->orderBy('created_at')
                ->get()
                ->map(fn (Testimonial $testimonial): array => [
                    'serviceName' => $serviceOptions[$testimonial->service] ?? $testimonial->service,
                    'serviceHref' => $hrefs[$testimonial->service] ?? route('about.testimonials'),
                    'quote' => $testimonial->story,
                    'initial' => mb_substr($testimonial->author_name, 0, 1),
                    'name' => $testimonial->author_name,
                    'subtitle' => $testimonial->author_location,
                ])
                ->take(3)
                ->all();

            $view->with('testimonials', $testimonials);
        });

        $businessProfile = null;

        View::composer('*', static function (\Illuminate\View\View $view) use (&$businessProfile): void {
            $businessProfile ??= BusinessProfile::current();

            $view->with('businessProfile', $businessProfile);
        });

        View::composer('components.waggies.footer', static function (\Illuminate\View\View $view): void {
            $businessProfile = $view->getData()['businessProfile'] ?? BusinessProfile::current();

            $view->with('businessSchedule', BusinessHour::contactSchedule(
                $businessProfile->timezone ?: config('app.timezone'),
            ));
        });

        Head::defaults(function (HeadBuilder $head): void {
            $head->title('Waggies - Pet Care, Abuja', exact: true)
                ->description('Waggies provides boarding, grooming, vet care, training, relocation and local transport services in Abuja, Nigeria.');
        });

        Head::errors(function ($errors): void {
            $errors->defaults(function (HeadBuilder $head): void {
                $head->title('Page not found - Waggies', exact: true)
                    ->description('The requested Waggies page could not be found.')
                    ->robots(['noindex', 'follow']);
            });

            $errors->status(401, function (HeadBuilder $head): void {
                $head->title('Unauthorized - Waggies', exact: true)
                    ->description('You need to sign in before viewing this Waggies page.')
                    ->robots(['noindex', 'follow']);
            });

            $errors->status(403, function (HeadBuilder $head): void {
                $head->title('Access denied - Waggies', exact: true)
                    ->description('You do not have permission to view this Waggies page.')
                    ->robots(['noindex', 'follow']);
            });

            $errors->status(419, function (HeadBuilder $head): void {
                $head->title('Page expired - Waggies', exact: true)
                    ->description('This Waggies form session has expired.')
                    ->robots(['noindex', 'follow']);
            });

            $errors->status(429, function (HeadBuilder $head): void {
                $head->title('Too many requests - Waggies', exact: true)
                    ->description('Please wait a moment before trying this Waggies request again.')
                    ->robots(['noindex', 'follow']);
            });

            $errors->status(500, function (HeadBuilder $head): void {
                $head->title('Something went wrong - Waggies', exact: true)
                    ->description('Waggies could not load this page because something went wrong.')
                    ->robots(['noindex', 'follow']);
            });

            $errors->status(503, function (HeadBuilder $head): void {
                $head->title('Temporarily unavailable - Waggies', exact: true)
                    ->description('Waggies is temporarily unavailable.')
                    ->robots(['noindex', 'follow']);
            });
        });
    }
}
