<?php

use App\Http\Controllers\AuthorController;
use Illuminate\Support\Facades\Route;

Route::get('/a-panel/authors', [AuthorController::class, 'index'])->name('a-panel.authors');
Route::get('/a-panel/authors/create', [AuthorController::class, 'create'])->name('a-panel.authors.create');
Route::post('/a-panel/authors', [AuthorController::class, 'store'])->name('a-panel.authors.store');
Route::get('/a-panel/authors/{id}', [AuthorController::class, 'show'])->name('a-panel.authors.show');
Route::get('/a-panel/authors/{author}/edit', [AuthorController::class, 'edit'])->name('a-panel.authors.edit');
Route::put('/a-panel/authors/{author}', [AuthorController::class, 'update'])->name('a-panel.authors.update');
Route::delete('/a-panel/authors/{author}', [AuthorController::class, 'destroy'])->name('a-panel.authors.destroy');
