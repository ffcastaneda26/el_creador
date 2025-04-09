<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Asesor\Resources\OrderResource;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Asesor\Resources\CotizationResource;
use App\Filament\Widgets\CalendarWidget;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                CalendarWidget::class,
            ])
            ->resources([
                CotizationResource::class,
                OrderResource::class,
            ])
            // ->brandName('Filament Demo')
            // ->brandLogo(asset('images/logo.jpg'))
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->favicon(fn () => asset('images/Logo.png'))
            ->brandLogoHeight('4rem')
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
            ])
            ->plugin(
                FilamentFullCalendarPlugin::make()
                    ->schedulerLicenseKey('null')
                    ->selectable()
                    ->editable()
                    ->timezone(config('app.timezone'))
                    ->locale(config('app.locale'))
                    ->plugins([])
                    ->config([
                        'firstDay' => 1,
                        'headerToolbar' => [
                            'right' => 'timeGridWeek,timeGridDay,dayGridMonth,yesterday,today',
                            'center' => 'title',
                            'left' => 'prev,next',
                        ],
                        'buttonText' => [
                            'today' => 'Hoy',
                            'month' => 'Mes',
                            'week' => 'Semana',
                            'day' => 'Día',
                        ],
                        'footerToolbar' => [
                            'start' => 'prev,next',
                            'center' => '',
                            // 'end' => 'prev,next',
                            'end' => 'timeGridWeek,timeGridDay,dayGridMonth,yesterday,today',
                        ],
                        'buttonIcons' => [
                            'prev' => 'chevron-left',
                            'next' => 'chevron-right',
                        ],
                        'views' => [
                            'timeGridWeek' => [
                                'titleFormat' => [
                                    'year' => 'numeric',
                                    'month' => 'short',
                                    'day' => 'numeric',
                                ],
                            ],
                            'timeGridDay' => [
                                'titleFormat' => [
                                    'year' => 'numeric',
                                    'month' => 'short',
                                    'day' => 'numeric',
                                ],
                            ],
                            'dayGridMonth' => [
                                'titleFormat' => [
                                    'year' => 'numeric',
                                    'month' => 'long',
                                ],
                            ],
                        ],
                        'slotDuration' => '00:15:00',
                        'slotMinTime' => '08:00:00',
                        'slotMaxTime' => '20:00:00',
                        'allDaySlot' => false,
                        'nowIndicator' => true,
                        'eventTimeFormat' => [
                            'hour' => 'numeric',
                            'minute' => '2-digit',
                            'hour12' => false,
                        ],
                        'eventClick' => 'function(info) {
                            console.log(info.event);
                        }',
                        'eventDrop' => 'function(info) {
                            console.log(info.event);
                        }',
                        'eventResize' => 'function(info) {
                            console.log(info.event);
                        }',
                        'select' => 'function(info) {
                            console.log(info.startStr, info.endStr);
                        }',
                        'selectAllow' => 'function(info) {
                            return info.start.getTime() > new Date().getTime();
                        }',
                        'selectMinDistance' => '15',
                        'selectOverlap' => false,
                        'selectConstraint' => 'businessHours',
                        'selectConstraint' => [
                            'start' => '08:00',
                            'end' => '20:00',
                        ],
                        'selectLongPressDelay' => '1000',

                    ])

            );
    }

    /**
     * firstDay: Día de inicio de la semana. 0: domingo, 1: lunes, 2: martes, 3: miércoles 4: jueves 4: viernes 7: sábado etc.
     */
}
