<?php

use App\Http\Controllers\TestController;
use App\Livewire\TestingPdfs;
use Illuminate\Support\Facades\Route;

Route::get('/probando',[TestController::class, 'index'])->name('probando');
Route::get('/testing-pdfs',TestingPdfs::class)->name('testing-pdfs');
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
});
