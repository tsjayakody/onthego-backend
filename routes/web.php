<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // category management routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
        Route::get('/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
        Route::delete('/{id}', [App\Http\Controllers\CategoryController::class, 'delete'])->name('categories.delete');
        Route::post('/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    });

    // hosters management routes
    Route::prefix('hosters')->group(function () {
        Route::get('/', [App\Http\Controllers\HosterController::class, 'show'])->name('hosters.show');
        Route::get('/create', [App\Http\Controllers\HosterController::class, 'create'])->name('hosters.create');
        Route::post('/', [App\Http\Controllers\HosterController::class, 'store'])->name('hosters.store');
        Route::get('/{id}', [App\Http\Controllers\HosterController::class, 'edit'])->name('hosters.edit');
        Route::delete('/{id}', [App\Http\Controllers\HosterController::class, 'delete'])->name('hosters.delete');
        Route::post('/{id}', [App\Http\Controllers\HosterController::class, 'update'])->name('hosters.update');
    });

    // shows management routes
    Route::prefix('shows')->group(function () {
        Route::get('/', [App\Http\Controllers\ShowController::class, 'show'])->name('shows.show');
        Route::get('/create', [App\Http\Controllers\ShowController::class, 'create'])->name('shows.create');
        Route::post('/', [App\Http\Controllers\ShowController::class, 'store'])->name('shows.store');
        Route::get('/{id}', [App\Http\Controllers\ShowController::class, 'edit'])->name('shows.edit');
        Route::delete('/{id}', [App\Http\Controllers\ShowController::class, 'delete'])->name('shows.delete');
        Route::post('/{id}', [App\Http\Controllers\ShowController::class, 'update'])->name('shows.update');
    });

    // episodes management routes
    Route::prefix('episodes')->group(function () {
        Route::get('/', [App\Http\Controllers\EpisodeController::class, 'show'])->name('episodes.show');
        Route::get('/create', [App\Http\Controllers\EpisodeController::class, 'create'])->name('episodes.create');
        Route::post('/', [App\Http\Controllers\EpisodeController::class, 'store'])->name('episodes.store');
        Route::get('/{id}', [App\Http\Controllers\EpisodeController::class, 'edit'])->name('episodes.edit');
        Route::delete('/{id}', [App\Http\Controllers\EpisodeController::class, 'delete'])->name('episodes.delete');
        Route::post('/{id}', [App\Http\Controllers\EpisodeController::class, 'update'])->name('episodes.update');
    });
});
