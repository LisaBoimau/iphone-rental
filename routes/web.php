<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\IphoneController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/iphones', [IphoneController::class, 'index'])->name('iphones.index');
Route::post('/iphones', [IphoneController::class, 'store'])->name('iphones.store');
Route::put('/iphones/{iphone}', [IphoneController::class, 'update'])->name('iphones.update');
Route::delete('/iphones/{iphone}', [IphoneController::class, 'destroy'])->name('iphones.destroy');

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');

Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

Route::get('/rental/scan-borrow', [RentalController::class, 'scanBorrow'])->name('rental.scan-borrow');
Route::get('/rental/lookup-iphone', [RentalController::class, 'lookupIphone'])->name('rental.lookup-iphone');
Route::post('/rental/scan-borrow', [RentalController::class, 'storeBorrow'])->name('rental.store-borrow');

Route::get('/rental/scan-return/{rental}', [RentalController::class, 'scanReturn'])->name('rental.scan-return');
Route::post('/rental/scan-return/{rental}', [RentalController::class, 'completeReturn'])->name('rental.complete-return');
