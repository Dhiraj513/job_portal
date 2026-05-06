<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest routes (only for NOT logged-in users)
Route::middleware('guest')->group(function () {

    Route::get('/account/login', [AccountController::class, 'login'])
        ->name('account.login');

    Route::get('/account/register', [AccountController::class, 'registration'])
        ->name('account.registration');

    Route::post('/account/process-register', [AccountController::class, 'processRegistration'])
        ->name('account.processRegistration');

    Route::post('/account/authenticate', [AccountController::class, 'authenticate'])
        ->name('account.authenticate');
});

// Auth routes (only for logged-in users)
Route::middleware('auth')->group(function () {

    Route::get('/account/profile', [AccountController::class, 'profile'])
        ->name('account.profile');

    Route::get('/account/logout', [AccountController::class, 'logout'])
        ->name('account.logout');

    Route::post('/account/update-profile', [AccountController::class, 'updateProfile'])
        ->name('account.updateProfile');
});