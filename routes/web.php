<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;


Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/', function () {
    return view('welcome');
})->name('landingpage');


Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');

Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');



Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');
