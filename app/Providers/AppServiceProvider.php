<?php

namespace App\Providers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Http\Responses\FilamentLoginResponse;
use App\Models\Cotization;
use App\Models\Manufacturing;
use App\Models\Order;
use App\Models\WarehouseRequest;
use App\Observers\CotizationObserver;
use App\Observers\ManufacturingObserver;
use App\Observers\OrderObserver;
use App\Observers\PurchaseDetailObserver;
use App\Observers\WarehouseRequestObserver;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as FilamentLoginResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FilamentLoginResponseContract::class, FilamentLoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::guessPolicyNamesUsing(function (string $modelClass): string {
            return 'App\\Policies\\' . class_basename($modelClass) . 'Policy';
        });

        PurchaseDetail::observe(PurchaseDetailObserver::class);
        Cotization::observe(CotizationObserver::class);
        WarehouseRequest::observe(WarehouseRequestObserver::class);
        Order::observe(OrderObserver::class);
        Manufacturing::observe(ManufacturingObserver::class);
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
