<?php

use App\Http\Controllers\Ecosystem\SciforumController;
use App\Http\Controllers\Ecosystem\PreprintController;
use App\Http\Controllers\Ecosystem\ScilitController;
use App\Http\Controllers\Ecosystem\SciProfileController;
use App\Http\Controllers\Ecosystem\EncyclopediaController;
use App\Http\Controllers\Ecosystem\JAMSController;
use Illuminate\Support\Facades\Route;

Route::prefix('sciforum')->name('sciforum.')->group(function () {
    Route::get('/', [SciforumController::class, 'index'])->name('index');
    Route::get('/{slug}', [SciforumController::class, 'show'])->name('show');
});

Route::prefix('preprints')->name('preprints.')->group(function () {
    Route::get('/', [PreprintController::class, 'index'])->name('index');
    Route::get('/{slug}', [PreprintController::class, 'show'])->name('show');
});

Route::prefix('scilit')->name('scilit.')->group(function () {
    Route::get('/', [ScilitController::class, 'index'])->name('index');
    Route::get('/search', [ScilitController::class, 'search'])->name('search');
});

Route::prefix('sciprofiles')->name('sciprofiles.')->group(function () {
    Route::get('/', [SciProfileController::class, 'index'])->name('index');
    Route::get('/{identifier}', [SciProfileController::class, 'show'])->name('show');
});

Route::prefix('encyclopedia')->name('encyclopedia.')->group(function () {
    Route::get('/', [EncyclopediaController::class, 'index'])->name('index');
    Route::get('/{slug}', [EncyclopediaController::class, 'show'])->name('show');
});

Route::prefix('jams')->name('jams.')->group(function () {
    Route::get('/', [JAMSController::class, 'index'])->name('index');
    Route::middleware(['auth'])->group(function () {
        Route::get('/submit', [JAMSController::class, 'submit'])->name('submit');
        Route::get('/dashboard', [JAMSController::class, 'dashboard'])->name('dashboard');
    });
});
