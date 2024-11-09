<?php

namespace App\Providers\Filament;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\ProductResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AsesorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('asesor')
            ->path('asesor')
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('5rem')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Asesor/Resources'), for: 'App\\Filament\\Asesor\\Resources')
            ->discoverPages(in: app_path('Filament/Asesor/Pages'), for: 'App\\Filament\\Asesor\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Asesor/Widgets'), for: 'App\\Filament\\Asesor\\Widgets')
            ->darkMode(true)
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->resources([
                ProductResource::class,
                ClientResource::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
