<?php

namespace App\Providers;

use App\Models\Testimonial;
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

        $businessDescription = 'Pet boarding, grooming, vet care, training, relocation and local transport in Abuja, Nigeria.';

        Head::defaults(function (HeadBuilder $head) use ($businessDescription): void {
            $siteUrl = app('router')->has('home')
                ? rtrim(route('home'), '/')
                : rtrim(url('/'), '/');
            $organization = Schema::organization()
                ->name('Waggies')
                ->description($businessDescription)
                ->url($siteUrl)
                ->toArray();

            $address = Schema::postalAddress()
                ->streetAddress(config('waggies.address.street'))
                ->addressLocality(config('waggies.address.city'))
                ->postalCode(config('waggies.address.postal_code'))
                ->addressRegion(config('waggies.address.state'))
                ->addressCountry(config('waggies.address.country'));

            $localBusiness = Schema::localBusiness()
                ->name('Waggies')
                ->description($businessDescription)
                ->url($siteUrl)
                ->address($address)
                ->toArray();

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
