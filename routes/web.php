<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\LpjController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignatureController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('landingpage');

// Authentication routes
Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Signature management
    Route::get('/signature', [SignatureController::class, 'show'])->name('signature.show');
    Route::post('/signature', [SignatureController::class, 'store'])->name('signature.store');
    Route::put('/signature', [SignatureController::class, 'update'])->name('signature.update');

    // Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::delete('/events/{event}/register', [EventController::class, 'unregister'])->name('events.unregister');

    // Event management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    });

    // Event status changes (executives and admin only)
    Route::middleware(['role:executive,admin'])->group(function () {
        Route::put('/events/{event}/status', [EventController::class, 'changeStatus'])->name('events.status');
    });

    // Letters
    Route::get('/letters', [LetterController::class, 'index'])->name('letters.index');
    Route::get('/letters/{letter}', [LetterController::class, 'show'])->name('letters.show');
    Route::get('/letters/{letter}/download', [LetterController::class, 'download'])->name('letters.download');

    // Letter management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        Route::get('/letters/create', [LetterController::class, 'create'])->name('letters.create');
        Route::post('/letters', [LetterController::class, 'store'])->name('letters.store');
        Route::get('/letters/{letter}/edit', [LetterController::class, 'edit'])->name('letters.edit');
        Route::put('/letters/{letter}', [LetterController::class, 'update'])->name('letters.update');
        Route::delete('/letters/{letter}', [LetterController::class, 'destroy'])->name('letters.destroy');
        Route::post('/letters/{letter}/sign', [LetterController::class, 'sign'])->name('letters.sign');
        Route::put('/letters/{letter}/sent', [LetterController::class, 'markAsSent'])->name('letters.sent');
        Route::put('/letters/{letter}/archive', [LetterController::class, 'archive'])->name('letters.archive');
    });

    // LPJs routes
    Route::get('/lpjs', [LpjController::class, 'index'])->name('lpjs.index');
    Route::get('/lpjs/{lpj}', [LpjController::class, 'show'])->name('lpjs.show');
    Route::get('/lpjs/{lpj}/download', [LpjController::class, 'download'])->name('lpjs.download');

    // LPJ management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        Route::get('/events/{event}/lpj/create', [LpjController::class, 'create'])->name('lpjs.create');
        Route::post('/events/{event}/lpj', [LpjController::class, 'store'])->name('lpjs.store');
        Route::get('/lpjs/{lpj}/edit', [LpjController::class, 'edit'])->name('lpjs.edit');
        Route::put('/lpjs/{lpj}', [LpjController::class, 'update'])->name('lpjs.update');
        Route::delete('/lpjs/{lpj}', [LpjController::class, 'destroy'])->name('lpjs.destroy');
    });

    // LPJ approval (executives and admin only)
    Route::middleware(['role:executive,admin'])->group(function () {
        Route::put('/lpjs/{lpj}/approve', [LpjController::class, 'approve'])->name('lpjs.approve');
        Route::put('/lpjs/{lpj}/reject', [LpjController::class, 'reject'])->name('lpjs.reject');
    });

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

    // News management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    });

    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Document management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // Galleries
    Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');
    Route::get('/galleries/{gallery}', [GalleryController::class, 'show'])->name('galleries.show');

    // Gallery management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        Route::get('/galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
        Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
        Route::get('/galleries/{gallery}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
        Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
        Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

        // Gallery images
        Route::post('/galleries/{gallery}/images', [GalleryController::class, 'storeImage'])->name('galleries.images.store');
        Route::delete('/galleries/images/{image}', [GalleryController::class, 'destroyImage'])->name('galleries.images.destroy');
    });
});

// Filament Admin Panel is automatically registered through the AdminPanelProvider