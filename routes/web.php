<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\WebAuthController;

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register.form');
    Route::post('/register', [WebAuthController::class, 'register'])->name('register');

    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
});

