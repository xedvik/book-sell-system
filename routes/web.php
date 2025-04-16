<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('/a-panel');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/a-panel', [DashboardController::class, 'index'])->name('a-panel.dashboard');
    require __DIR__.'/books.php';
    require __DIR__.'/author.php';
    require __DIR__.'/users.php';
    require __DIR__.'/profile.php';
    require __DIR__.'/sales.php';
});

require __DIR__.'/auth.php';
