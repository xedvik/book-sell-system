<?php
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// Книги - CRUD маршруты через контроллер
    Route::get('/a-panel/books', [BookController::class, 'index'])->name('a-panel.books');
    Route::get('/a-panel/books/create', [BookController::class, 'create'])->name('a-panel.books.create');
    Route::post('/a-panel/books', [BookController::class, 'store'])->name('a-panel.books.store');
    Route::get('/a-panel/books/{id}', [BookController::class, 'show'])->name('a-panel.books.show');
    Route::get('/a-panel/books/{book}/edit', [BookController::class, 'edit'])->name('a-panel.books.edit');
    Route::put('/a-panel/books/{book}', [BookController::class, 'update'])->name('a-panel.books.update');
    Route::delete('/a-panel/books/{book}', [BookController::class, 'destroy'])->name('a-panel.books.destroy');
