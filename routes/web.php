<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\PrivateNotice;
use App\Http\Controllers\TestController;
use App\Livewire\Clients\Clients;
use App\Livewire\Receipts\Receipts;
use App\Livewire\TestingPdfs;
use App\Livewire\WarehouseRequests\WarehouseRequests;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return redirect()->to('/portal/login');
})->name('login');

// Los documentos se generan a partir de registros de clientes, cotizaciones y pedidos:
// requieren sesión y el propio controlador autoriza el registro contra su policy.
Route::middleware('auth')->group(function () {
    Route::get('pdf/download/{record}/{document}/{output?}', [PdfController::class, 'index'])->name('pdf-document');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', function () {
        return redirect()->to('/dashboard');
    });


    Route::get('/dashboard', function () {

        if(Auth::user()->hasRole('Super Admin')){
            return redirect()->to('/admin');
        }

        if(Auth::user()->hasRole('Administrador')){
            return redirect()->to('/admin');
        }

        if(Auth::user()->hasRole('Gerente')){
            return redirect()->to('/gerente');
        }

        if(Auth::user()->hasRole('Direccion')){
            return redirect()->to('/direccion');
        }

        if(Auth::user()->hasRole('Asesor')){
            return redirect()->to('/asesor');
        }

        if(Auth::user()->hasRole('Vendedor')){
            return redirect()->to('/vendedor');
        }

        if(Auth::user()->hasRole('Capturista')){
            return redirect()->to('/capturista');
        }

        if(Auth::user()->hasRole('Produccion')){
            return redirect()->to('/produccion');
        }

        if(Auth::user()->hasRole('Envios')){
            return redirect()->to('/envios');
        }

        if(Auth::user()->hasRole('Almacen')){
            return redirect()->to('/almacen');
        }

        return redirect()->to('/admin');
    })->name('dashboard');
    Route::get('clients',Clients::class)->name('clients');
    Route::get('warehouse_requests', WarehouseRequests::class)->name('warehouse-requests');
    Route::get('receipts',Receipts::class)->name('receipts');
});
