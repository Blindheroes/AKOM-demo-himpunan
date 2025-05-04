<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\LPJController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('landingpage');

// Authentication routes
Route::get('/login', function () {
    return redirect()->route('google.redirect');
})->name('login');
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

    // Event management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    });

    // This specific route needs to come after /events/create to avoid catching 'create' as an {event} parameter
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::delete('/events/{event}/register', [EventController::class, 'unregister'])->name('events.unregister');

    // Event status changes (executives and admin only)
    Route::middleware(['role:executive,admin'])->group(function () {
        Route::put('/events/{event}/status', [EventController::class, 'changeStatus'])->name('events.status');
        Route::post('/events/{event}/status', [EventController::class, 'changeStatus'])->name('events.status');
    });

    // Letters
    Route::get('/letters', [LetterController::class, 'index'])->name('letters.index');

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

    // These routes must come after the /letters/create route
    Route::get('/letters/{letter}', [LetterController::class, 'show'])->name('letters.show');
    Route::get('/letters/{letter}/download', [LetterController::class, 'download'])->name('letters.download');

    // LPJs routes
    Route::get('/lpjs', [LPJController::class, 'index'])->name('lpjs.index');

    // LPJ management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        // New improved LPJ creation flow
        Route::get('/lpjs/select-event-template', [LPJController::class, 'selectEventTemplate'])->name('lpjs.select-event-template');
        Route::post('/lpjs/store-with-template', [LPJController::class, 'storeWithTemplate'])->name('lpjs.store-with-template');

        // Existing routes
        Route::get('/events/{event}/lpj/create', [LPJController::class, 'create'])->name('lpjs.create');
        Route::post('/events/{event}/lpj', [LPJController::class, 'store'])->name('lpjs.store');
        Route::get('/lpjs/{lpj}/edit', [LPJController::class, 'edit'])->name('lpjs.edit');
        Route::put('/lpjs/{lpj}', [LPJController::class, 'update'])->name('lpjs.update');
        Route::delete('/lpjs/{lpj}', [LPJController::class, 'destroy'])->name('lpjs.destroy');
    });

    // These specific routes need to come after other more specific LPJ routes
    Route::get('/lpjs/{lpj}', [LPJController::class, 'show'])->name('lpjs.show');
    Route::get('/lpjs/{lpj}/download', [LPJController::class, 'download'])->name('lpjs.download');

    // LPJ approval (executives and admin only)
    Route::middleware(['role:executive,admin'])->group(function () {
        Route::put('/lpjs/{lpj}/approve', [LPJController::class, 'approve'])->name('lpjs.approve');
        Route::put('/lpjs/{lpj}/reject', [LPJController::class, 'reject'])->name('lpjs.reject');
    });

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');

    // News management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    });

    // This route must come after '/news/create' to avoid catching 'create' as a {news} parameter
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');

    // Document management (staff and above)
    Route::middleware(['role:staff,executive,admin'])->group(function () {
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // Document approval (executives and admin only)
    Route::middleware(['role:executive,admin'])->group(function () {
        Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
        Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    });

    // These routes should come after /documents/create
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Galleries
    Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');

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

    // This route should come after /galleries/create to avoid catching 'create' as a {gallery} parameter
    Route::get('/galleries/{gallery}', [GalleryController::class, 'show'])->name('galleries.show');

    // Custom Admin Panel (admin only) - Replacement for Filament
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Admin Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        // Department Management
        Route::get('/departments', [AdminController::class, 'departments'])->name('admin.departments.index');
        Route::get('/departments/create', [AdminController::class, 'createDepartment'])->name('admin.departments.create');
        Route::post('/departments', [AdminController::class, 'storeDepartment'])->name('admin.departments.store');
        Route::get('/departments/{department}/edit', [AdminController::class, 'editDepartment'])->name('admin.departments.edit');
        Route::put('/departments/{department}', [AdminController::class, 'updateDepartment'])->name('admin.departments.update');
        Route::delete('/departments/{department}', [AdminController::class, 'destroyDepartment'])->name('admin.departments.destroy');

        // System Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

        // Reports & Analytics
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    });
});
