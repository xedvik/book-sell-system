<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Отображение панели управления
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Получаем основные статистические данные
        $booksCount = Book::count();
        $authorsCount = Author::count();
        $usersCount = User::count();
        $salesCount = Sell::count();

        // Получаем последние добавленные книги
        $latestBooks = Book::with('authors')->latest()->take(5)->get();

        // Получаем последние продажи
        $latestSales = Sell::with(['book', 'client'])->latest()->take(5)->get();

        // Передаем данные в представление
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
