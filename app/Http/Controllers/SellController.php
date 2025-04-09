<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellRequest;
use App\Http\Requests\UpdateSellRequest;
use App\Models\Book;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
class SellController extends Controller
{

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('search');

        $sales = Sell::getFilteredSales($search);

        $sales->appends($request->query());

        $stats = Cache::remember('stats', 60, function () {
            return Sell::getSalesStats();
        });

        return view('a-panel.sales.index', compact('sales', 'stats'));
    }


    public function create()
    {
        $books = Cache::remember('books', 60, function () {
            return Book::all();
        });
        $clients = Cache::remember('clients', 60, function () {
            return User::all();
        });
        return view('a-panel.sales.create', compact('books', 'clients'));
    }


    public function store(StoreSellRequest $request)
    {
        Sell::create($request->validated());
        Sell::invalidateCache();
        return redirect()->route('a-panel.sales')->with('success', 'Продажа успешно создана');
    }


    public function show(Sell $sell)
    {
        $sell = Cache::remember('sell-' . $sell->id, 60, function () use ($sell) {
            $sell->load(['book', 'client']);
          return $sell;
        });
        return view('a-panel.sales.show', compact('sell'));
    }


    public function edit(Sell $sell)
    {
        $books = Cache::remember('books', 60, function () {
            return Book::all();
        });
        $clients = Cache::remember('clients', 60, function () {
            return User::all();
        });
        return view('a-panel.sales.edit', compact('sell', 'books', 'clients'));
    }


    public function update(UpdateSellRequest $request, Sell $sell)
    {
        $sell->update($request->validated());
        Sell::invalidateCache();
        return redirect()->route('a-panel.sales')->with('success', 'Продажа успешно обновлена');
    }


    public function destroy(Sell $sell)
    {
        $sell->delete();
        Sell::invalidateCache();
        return redirect()->route('a-panel.sales')->with('success', 'Продажа успешно удалена');
    }
}
