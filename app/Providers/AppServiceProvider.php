<?php

namespace App\Providers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Observers\PurchaseDetailObserver;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

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
        PurchaseDetail::observe(PurchaseDetailObserver::class);
        Toggle::configureUsing(function (Toggle $toggle): void {
            $toggle
            ->translateLabel()
            ->inline(false)
            ->onIcon('heroicon-m-check-circle')
            ->offIcon('heroicon-m-x-circle')
            ->onColor('success')
            ->offColor('danger');
        });
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Amber,
            'success' => Color::Green,
            'warning' => Color::Amber,
            'indigo'  => Color::Indigo,
        ]);
    }
}
