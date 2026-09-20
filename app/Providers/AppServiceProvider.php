<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
