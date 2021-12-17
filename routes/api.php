<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('hosters', [App\Http\Controllers\api\HosterController::class, 'index']);
Route::get('hosters/{id}', [App\Http\Controllers\api\HosterController::class, 'getById']);

Route::get('shows', [App\Http\Controllers\api\ShowController::class, 'index']);
Route::get('shows/{id}', [App\Http\Controllers\api\ShowController::class, 'getById']);
