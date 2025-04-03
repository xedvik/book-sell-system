<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellRequest;
use App\Http\Requests\UpdateSellRequest;
use App\Models\Book;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Http\Request;

class SellController extends Controller
{

    public function index(Request $request)
    {
        $sales = Sell::getFilteredSales($request->search);
        $stats = Sell::getSalesStats();

        return view('a-panel.sales.index', compact('sales', 'stats'));
    }


    public function create()
    {
        $books = Book::all();
        $clients = User::all();
        return view('a-panel.sales.create', compact('books', 'clients'));
    }


    public function store(StoreSellRequest $request)
    {
        Sell::create($request->validated());
        return redirect()->route('a-panel.sales')->with('success', 'Продажа успешно создана');
    }


    public function show(Sell $sell)
    {
        $sell->load(['book', 'client']);
        return view('a-panel.sales.show', compact('sell'));
    }


    public function edit(Sell $sell)
    {
        $books = Book::all();
        $clients = User::all();
        return view('a-panel.sales.edit', compact('sell', 'books', 'clients'));
    }


    public function update(UpdateSellRequest $request, Sell $sell)
    {
        $sell->update($request->validated());
        return redirect()->route('a-panel.sales')->with('success', 'Продажа успешно обновлена');
    }


    public function destroy(Sell $sell)
    {
        $sell->delete();
        return redirect()->route('a-panel.sales')->with('success', 'Продажа успешно удалена');
    }
}
