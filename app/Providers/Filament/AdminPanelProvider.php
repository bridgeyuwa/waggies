<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\DashboardWorkQueueWidget;
use App\Filament\Widgets\OperationalStatsWidget;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    /**
     * Waggies' primary purple scale, with the 600 shade anchored to the
     * public design system's primary token.
     *
     * @var array<int, string>
     */
    private const WAGGIES_PRIMARY = [
        50 => 'oklch(0.977 0.014 308.299)',
        100 => 'oklch(0.946 0.033 307.174)',
        200 => 'oklch(0.902 0.063 306.703)',
        300 => 'oklch(0.827 0.119 306.383)',
        400 => 'oklch(0.714 0.203 305.504)',
        500 => 'oklch(0.545 0.18 309.9)',
        600 => 'oklch(0.432 0.162 309.9)',
        700 => 'oklch(0.36 0.14 309.9)',
        800 => 'oklch(0.28 0.12 309.9)',
        900 => 'oklch(0.196 0.108 310)',
        950 => 'oklch(0.15 0.09 310)',
    ];

    /**
     * Waggies' warm reading and surface palette for Filament's gray role.
     *
     * @var array<int, string>
     */
    private const WAGGIES_GRAY = [
        50 => 'oklch(0.985 0.005 90)',
        100 => 'oklch(0.95 0.012 80)',
        200 => 'oklch(0.91 0.018 80)',
        300 => 'oklch(0.68 0.02 75)',
        400 => 'oklch(0.62 0.02 75)',
        500 => 'oklch(0.52 0.018 70)',
        600 => 'oklch(0.46 0.02 70)',
        700 => 'oklch(0.36 0.02 70)',
        800 => 'oklch(0.27 0.02 70)',
        900 => 'oklch(0.22 0.02 70)',
        950 => 'oklch(0.17 0.02 70)',
    ];

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Waggies')
            ->font('DM Sans')
            ->defaultThemeMode(ThemeMode::Light)
            ->darkMode(false)
            ->colors([
                'primary' => self::WAGGIES_PRIMARY,
                'gray' => self::WAGGIES_GRAY,
                'danger' => Color::hex('#A63D4E'),
                'info' => Color::hex('#536D8A'),
                'success' => Color::hex('#2E7D4F'),
                'warning' => Color::hex('#8A5A00'),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                OperationalStatsWidget::class,
                DashboardWorkQueueWidget::class,
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
