<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KoordinatController;
use App\Http\Controllers\AuthController;

// Rute untuk halaman publik
Route::get('/', function () {
    return view('publicpage');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [KoordinatController::class, 'index']);
    Route::get('/koordinat/json', [KoordinatController::class, 'titikkoordinat']);
});

// Rute untuk halaman login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);