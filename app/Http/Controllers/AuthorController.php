<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use App\Models\Book;
class AuthorController extends Controller
{

    public function index()
    {
        $authorsQuery = Author::withCount('books');

        if (request()->has('search') && !empty(request('search'))) {
            $searchTerm = request('search');
            $searchTerms = explode(' ', strtolower($searchTerm));

            $authorsQuery->where(function($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->where(function($q) use ($term) {
                        $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . $term . '%'])
                          ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $term . '%']);
                    });
                }
            });
        }

        $authors = $authorsQuery->latest()->paginate(10);

        $authors->appends(request()->query());

        return view('a-panel.authors.index', compact('authors'));
    }


    public function create()
    {
        $books = Book::all();
        return view('a-panel.authors.create', compact('books'));
    }


    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());
        return redirect()->route('a-panel.authors');
    }


    public function show(Author $author)
    {
        $author->loadCount('books');
        return view('a-panel.authors.show', compact('author'));
    }


    public function edit(Author $author)
    {
        $books = Book::all();
        return view('a-panel.authors.edit', compact('author', 'books'));
    }


    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());
        return redirect()->route('a-panel.authors');
    }


    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('a-panel.authors');
    }
}
