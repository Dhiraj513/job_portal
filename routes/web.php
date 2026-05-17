<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JobsController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{id}', [JobsController::class, 'detail'])->name('jobDetail');

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
    
    Route::post('/account/updateprofilepic', [AccountController::class, 'updateProfilePic'])
        ->name('account.updateProfilePic');

    Route::get('/account/create-job', [AccountController::class, 'createJob'])
        ->name('account.createJob');    

    Route::post('/account/save-job', [AccountController::class, 'saveJob'])
        ->name('account.saveJob');

     Route::get('/account/my-jobs', [AccountController::class, 'myJobs'])
        ->name('account.myJobs');   

     Route::get('/account/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])
        ->name('account.editJob'); 

    Route::post('/account/update-job/{jobId}', [AccountController::class, 'updateJob'])
        ->name('account.updateJob');

    Route::post('/account/delete-Job', [AccountController::class, 'deleteJob'])
        ->name('account.deleteJob');           

});