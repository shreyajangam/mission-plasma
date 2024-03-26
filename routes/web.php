<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlasmaDonorController;
use App\Http\Controllers\PlasmaRequestController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route to the dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Routes for donor and request forms
Route::get('/donor-form', [PlasmaDonorController::class, 'showDonorForm'])->name('donor-form');
Route::post('/donor-submit', [PlasmaDonorController::class, 'storeDonorForm'])->name('donor-submit');
Route::get('/request-form', [PlasmaRequestController::class, 'showRequestForm'])->name('request-form');
Route::post('/request-submit', [PlasmaRequestController::class, 'storeRequestForm'])->name('request-submit');

// Routes for requests and donors list
Route::get('/requests-list', [PlasmaDonorController::class, 'showRequestsList'])->name('requests-list');
Route::get('/donors-list', [PlasmaRequestController::class, 'showDonorsList'])->name('donors-list');
