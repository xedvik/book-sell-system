<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
{
    public function index()
    {

        $page = request()->get('page', 1);
        $search = request()->get('search');

        $authors = Author::getFilteredAuthors($search);

        $authors->appends(request()->query());

        return view('a-panel.authors.index', compact('authors'));
    }

    public function create()
    {
        $books = Cache::remember('books', 60, function () {
            return Book::all();
        });

        return view('a-panel.authors.create', compact('books'));
    }

    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());
        Author::invalidateCache();

        return redirect()->route('a-panel.authors');
    }

    public function show($id)
    {
        $author = Cache::rememberKeyPattern('author-' . $id, 'author-*', 60, function () use ($id) {
            return Author::with('books')->withCount('books')->findOrFail($id);
        });

        return view('a-panel.authors.show', compact('author'));
    }

    public function edit(Author $author)
    {
        $books = Cache::remember('books', 60, function () {
            return Book::all();
        });

        return view('a-panel.authors.edit', compact('author', 'books'));
    }

    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());
        Author::invalidateCache();

        return redirect()->route('a-panel.authors');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        Author::invalidateCache();

        return redirect()->route('a-panel.authors');
    }
}
