<?php

namespace App\Http\Controllers;

use App\Events\NewBookAdded;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Support\Facades\Cache;
class BookController extends Controller
{

    public function index()
    {
        $page = request()->get('page', 1);
        $search = request()->get('search');
        $sort = request()->get('sort');
        $cacheKey = 'books:page:' . $page . ':search:' . ($search ?? 'none') . ':sort:' . ($sort ?? 'none');

        $books = Book::getFilteredBooks($search, $sort);
        $books->appends(request()->query());

        return view('a-panel.books.index', compact('books'));
    }


    public function create()
    {
        $authors = Cache::remember('authors', 60, function () {
            return Author::all();
        });
        return view('a-panel.books.create', compact('authors'));
    }


    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->safe()->except('authors'));

        if ($request->has('authors')) {
            $book->authors()->sync($request->authors);
        }

        event(new NewBookAdded($book));
        Book::invalidateCache();
        return redirect()->route('a-panel.books');
    }


    public function show($id)
    {
        $book = Cache::rememberKeyPattern('book-' . $id, 'book-*', 60, function () use ($id) {
            return Book::with('authors')->withCount('authors')->findOrFail($id);
        });
        return view('a-panel.books.show', compact('book'));
    }


    public function edit(Book $book)
    {
        $authors = Cache::remember('authors', 60, function () {
            return Author::all();
        });
        return view('a-panel.books.edit', compact('book', 'authors'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->safe()->except('authors'));

        if ($request->has('authors')) {
            $book->authors()->sync($request->authors);
        }

        Book::invalidateCache();
        return redirect()->route('a-panel.books');
    }


    public function destroy(Book $book)
    {
        $book->delete();
        Book::invalidateCache();
        return redirect()->route('a-panel.books');
    }
}
