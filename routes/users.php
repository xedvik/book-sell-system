<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Маршруты для управления пользователями
    Route::get('/a-panel/users', [UserController::class, 'index'])->name('a-panel.users');
    Route::get('/a-panel/users/create', [UserController::class, 'create'])->name('a-panel.users.create');
    Route::post('/a-panel/users', [UserController::class, 'store'])->name('a-panel.users.store');
    Route::get('/a-panel/users/{user}', [UserController::class, 'show'])->name('a-panel.users.show');
    Route::get('/a-panel/users/{user}/edit', [UserController::class, 'edit'])->name('a-panel.users.edit');
    Route::put('/a-panel/users/{user}', [UserController::class, 'update'])->name('a-panel.users.update');
    Route::delete('/a-panel/users/{user}', [UserController::class, 'destroy'])->name('a-panel.users.destroy');
