<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::get('hosters', [App\Http\Controllers\api\HosterController::class, 'index']);
Route::get('hosters/{id}', [App\Http\Controllers\api\HosterController::class, 'getById']);

Route::get('shows', [App\Http\Controllers\api\ShowController::class, 'index']);
Route::get('shows/{id}', [App\Http\Controllers\api\ShowController::class, 'getById']);

Route::middleware(['throttle:api'])->prefix('public/v1/')->group(function () {
    // public api routes for hosters
    Route::prefix('hosters')->group(function () {
        Route::get('/', [App\Http\Controllers\api\public\HosterController::class, 'index']);
        Route::get('/{slug}', [App\Http\Controllers\api\public\HosterController::class, 'getBySlug']);
    });

    // public api routes for shows
    Route::prefix('shows')->group(function () {
        Route::get('/', [App\Http\Controllers\api\public\ShowController::class, 'index']);
        Route::get('/{slug}', [App\Http\Controllers\api\public\ShowController::class, 'getBySlug']);
        Route::get('/{slug}/episodes', [App\Http\Controllers\api\public\ShowController::class, 'getEpisodesByShowSlug']);
    });

    // public api routes for categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [App\Http\Controllers\api\public\CategoryController::class, 'index']);
        Route::get('/{slug}', [App\Http\Controllers\api\public\CategoryController::class, 'getBySlug']);
    });

    // public api routes for categories
    Route::prefix('episodes')->group(function () {
        Route::get('/', [App\Http\Controllers\api\public\EpisodeController::class, 'index']);
        Route::get('/popular', [App\Http\Controllers\api\public\EpisodeController::class, 'getPopularEpisodes']);
        Route::get('/featured', [App\Http\Controllers\api\public\EpisodeController::class, 'getFeaturedEpisodes']);
        Route::get('/{slug}', [App\Http\Controllers\api\public\EpisodeController::class, 'getBySlug']);
    });

    // public api route for get all slider items
    Route::get('slider', [App\Http\Controllers\api\public\GeneralController::class, 'getAllSliderItems']);
});
