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
use Illuminate\Support\Facades\Schema as DatabaseSchema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Head\Facades\Head;
use Laravel\Head\HeadBuilder;
use Spatie\SchemaOrg\Schema;

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
            $view->with('businessHours', BusinessHour::publicSchedule());
        });

        $businessDescription = 'Pet boarding, grooming, vet care, training, relocation and local transport in Abuja, Nigeria.';

        Head::defaults(function (HeadBuilder $head) use ($businessDescription): void {
            $siteUrl = app('router')->has('home')
                ? rtrim(route('home'), '/')
                : rtrim(url('/'), '/');
            $businessProfile = BusinessProfile::current();
            $organization = Schema::organization()
                ->name($businessProfile->business_name)
                ->description($businessDescription)
                ->url($siteUrl)
                ->toArray();

            $address = Schema::postalAddress()
                ->streetAddress($businessProfile->address_street)
                ->addressLocality($businessProfile->address_city)
                ->postalCode($businessProfile->address_postal_code)
                ->addressRegion($businessProfile->address_state)
                ->addressCountry($businessProfile->address_country);

            $localBusiness = Schema::localBusiness()
                ->name($businessProfile->business_name)
                ->description($businessDescription)
                ->url($siteUrl)
                ->telephone($businessProfile->phone_international ?: $businessProfile->phone)
                ->email($businessProfile->primary_email)
                ->address($address)
                ->sameAs(array_values($businessProfile->socialLinks()));

            if (DatabaseSchema::hasTable('business_hours') && DatabaseSchema::hasColumn('business_hours', 'end_date')) {
                $openingHours = BusinessHour::openingHours($businessProfile->timezone ?: config('app.timezone'));

                $localBusiness->openingHoursSpecification($openingHours->asStructuredData(
                    timezone: $businessProfile->timezone ?: config('app.timezone'),
                ));
            }

            $localBusiness = $localBusiness->toArray();

            $head->title('Waggies - Pet Care, Abuja', exact: true)
                ->description('Waggies provides boarding, grooming, vet care, training, relocation and local transport services in Abuja, Nigeria.')
                ->themeColor('#6B2C91')
                ->applicationName('Waggies')
                ->meta('author', 'Waggies')
                ->meta('generator', 'Laravel')
                ->meta('keywords', 'pet boarding Abuja,pet care Abuja,pet relocation Nigeria,dog grooming Abuja,veterinary care,Waggies')
                ->referrer('origin-when-cross-origin')
                ->og('website', 'Waggies - Pet Care, Abuja', 'Pet boarding, grooming, vet care and relocation services in Abuja, Nigeria.', $siteUrl, asset('logo.svg'), siteName: 'Waggies', locale: 'en_NG')
                ->twitter('summary_large_image', title: 'Waggies - Pet Care, Abuja', description: 'Pet boarding, grooming, vet care and relocation services in Abuja, Nigeria.', image: asset('logo.svg'))
                ->favicon(asset('favicon.ico'))
                ->schema($organization)
                ->schema($localBusiness);
        });

        Head::errors(function ($errors): void {
            $errors->defaults(function (HeadBuilder $head): void {
                $head->title('Page not found - Waggies', exact: true)
                    ->description('The requested Waggies page could not be found.')
                    ->robots(['noindex', 'follow']);
            });
        });
    }
}
