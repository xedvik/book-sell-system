<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Отображение панели управления
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $booksCount = Cache::remember('booksCount', 60, function () {
            return Book::count();
        });
        $authorsCount = Cache::remember('authorsCount', 60, function () {
            return Author::count();
        });
        $usersCount = Cache::remember('usersCount', 60, function () {
            return User::count();
        });
        $salesCount = Cache::remember('salesCount', 60, function () {
            return Sell::count();
        });
        $latestBooks = Cache::remember('latestBooks', 60, function () {
            return Book::with('authors')->latest()->take(5)->get();
        });
        $latestSales = Cache::remember('latestSales', 60, function () {
            return Sell::with(['book', 'client'])->latest()->take(5)->get();
        });

        return view('a-panel.index', compact(
            'booksCount',
            'authorsCount',
            'usersCount',
            'salesCount',
            'latestBooks',
            'latestSales'
        ));
    }
}
