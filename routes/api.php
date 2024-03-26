<?php

use App\Http\Controllers\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlasmaRequestController;
use App\Http\Controllers\PlasmaDonorController;
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

// Routes for plasma requests and donors
Route::get('/requests', [PlasmaRequestController::class, 'index']);
Route::get('/donors', [PlasmaDonorController::class, 'index']);

// Routes for getting countries, states, and cities
Route::get('/countries', [LocationController::class, 'countries']);
Route::get('/states/{country_id}',  [LocationController::class, 'states']);
Route::get('/cities/{state_id}',  [LocationController::class, 'cities']);


