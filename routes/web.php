<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'loginPost')->name('loginPost');

    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerPost')->name('registerPost');

    Route::delete('/logout', 'logout')->name('logout');

    Route::get('forgot-password', 'forgotPassword')
        ->name('forgot.password');

    Route::post('forgot-password', 'forgotPasswordPost')
        ->middleware('guest')
        ->name('password.forgot.post');

    Route::get('/reset-password/{token}', 'resetPassword')
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', 'resetPasswordPost')
        ->middleware('guest')
        ->name('password.reset.post');
});

Route::get('/', HomeController::class)->name('home');