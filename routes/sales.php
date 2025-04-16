<?php

use App\Http\Controllers\SellController;
use Illuminate\Support\Facades\Route;

// Маршруты для управления продажами
Route::get('/a-panel/sales', [SellController::class, 'index'])->name('a-panel.sales');
Route::get('/a-panel/sales/create', [SellController::class, 'create'])->name('a-panel.sales.create');
Route::post('/a-panel/sales', [SellController::class, 'store'])->name('a-panel.sales.store');
Route::get('/a-panel/sales/{sell}', [SellController::class, 'show'])->name('a-panel.sales.show');
Route::get('/a-panel/sales/{sell}/edit', [SellController::class, 'edit'])->name('a-panel.sales.edit');
Route::put('/a-panel/sales/{sell}', [SellController::class, 'update'])->name('a-panel.sales.update');
Route::delete('/a-panel/sales/{sell}', [SellController::class, 'destroy'])->name('a-panel.sales.destroy');
