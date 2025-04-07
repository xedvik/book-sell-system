<?php

namespace App\Http\Controllers;

use App\Events\NewBookAdded;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Author;

class BookController extends Controller
{

    public function index()
    {
        $books = Book::getFilteredBooks(
            request('search'),
            request('sort')
        );

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

        event(new NewBookAdded($book));

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
