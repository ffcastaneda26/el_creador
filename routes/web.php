<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\PrivateNotice;
use App\Http\Controllers\TestController;
use App\Livewire\Clients\Clients;
use App\Livewire\TestingPdfs;
use Illuminate\Support\Facades\Route;
Route::get('/probando',[TestController::class, 'index'])->name('probando');
Route::get('/{record}/pdf/download', [PrivateNotice::class, 'download'])->name('student.pdf.download');
Route::get('pdf/download/{record}/{document}', [PdfController::class, 'index'])->name('pdf-document');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('clients',Clients::class)->name('clients');
});
