<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Authentication (tidak perlu login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Route Aplikasi (wajib login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/', fn () => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Customer
    Route::resource('customers', CustomerController::class)->except(['show']);

    // CRUD Kendaraan
    Route::get('/vehicles/by-customer/{customer}', [VehicleController::class, 'byCustomer'])
        ->name('vehicles.by-customer');
    Route::resource('vehicles', VehicleController::class)->except(['show']);

    // CRUD Mekanik
    Route::resource('mechanics', MechanicController::class)->except(['show']);

    // CRUD Produk / Jasa
    Route::resource('products', ProductController::class)->except(['show']);

    // Invoice — resource + cetak
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])
        ->name('invoices.print');
    Route::resource('invoices', InvoiceController::class);
});
