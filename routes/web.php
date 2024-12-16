<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\PrivateNotice;
use App\Http\Controllers\TestController;
use App\Livewire\Clients\Clients;
use App\Livewire\TestingPdfs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::get('/probando',[TestController::class, 'index'])->name('probando');
Route::get('/{record}/pdf/download', [PrivateNotice::class, 'download'])->name('student.pdf.download');
Route::get('pdf/download/{record}/{document}', [PdfController::class, 'index'])->name('pdf-document');

// Route::get('/', function () {
//     if (auth()->check()) {
//         if(Auth::user()->hasRole('Administrador')){
//             return redirect()->route('/admin');
//         }
//         if(Auth::user()->hasRole('Asesor')){
//             return redirect()->route('/asesor');
//         }
//     } else {
//         return view('welcome');
//     }
//     return view('welcome');
// });

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', function () {
        return redirect()->to('/dashboard');
    });
    

    Route::get('/dashboard', function () {
 
        if(Auth::user()->hasRole('Administrador')){
            return redirect()->to('/admin');
        }

        if(Auth::user()->hasRole('Asesor')){
            return redirect()->to('/asesor');
        }

        return view('dashboard');   
    })->name('dashboard');
    Route::get('clients',Clients::class)->name('clients');
});
