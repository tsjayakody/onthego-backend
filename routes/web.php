<?php

use Illuminate\Foundation\Application;
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
        Route::post('/', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
        Route::delete('/{id}', [App\Http\Controllers\CategoryController::class, 'delete'])->name('categories.delete');
        Route::put('/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    });

    // hosters management routes
    Route::prefix('hosters')->group(function () {
        Route::get('/', [App\Http\Controllers\HosterController::class, 'show'])->name('hosters.show');
        Route::get('/create', [App\Http\Controllers\HosterController::class, 'create'])->name('hosters.create');
        Route::post('/', [\App\Http\Controllers\HosterController::class, 'store'])->name('hosters.store');
        Route::get('/{id}', [App\Http\Controllers\HosterController::class, 'edit'])->name('hosters.edit');
        Route::delete('/{id}', [App\Http\Controllers\HosterController::class, 'delete'])->name('hosters.delete');
        Route::put('/{id}', [App\Http\Controllers\HosterController::class, 'update'])->name('hosters.update');
    });
});
