<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Author;
class BookController extends Controller
{

    public function index()
    {

        $booksQuery = Book::with('authors');


        if (request()->has('search') && !empty(request('search'))) {
            $searchTerm = request('search');
            $booksQuery->where(function($query) use ($searchTerm) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                      ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
            });
        }

        $sort = request('sort');
        switch ($sort) {
            case 'price_asc':
                $booksQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $booksQuery->orderBy('price', 'desc');
                break;
            case 'quantity_asc':
                $booksQuery->orderBy('quantity', 'asc');
                break;
            case 'quantity_desc':
                $booksQuery->orderBy('quantity', 'desc');
                break;
            default:
                $booksQuery->latest();
                break;
        }


        $books = $booksQuery->paginate(10);


        $books->appends(request()->query());

        return view('a-panel.books.index', compact('books'));
    }


    public function create()
    {
        $authors = Author::all();
        return view('a-panel.books.create', compact('authors'));
    }


    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->safe()->except('authors'));

        if ($request->has('authors')) {
            $book->authors()->sync($request->authors);
        }

        return redirect()->route('a-panel.books');
    }


    public function show(Book $book)
    {
        $book->load('authors');
        return view('a-panel.books.show', compact('book'));
    }


    public function edit(Book $book)
    {
        $authors = Author::all();
        return view('a-panel.books.edit', compact('book', 'authors'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->safe()->except('authors'));

        if ($request->has('authors')) {
            $book->authors()->sync($request->authors);
        }

        return redirect()->route('a-panel.books');
    }


    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('a-panel.books');
    }
}
