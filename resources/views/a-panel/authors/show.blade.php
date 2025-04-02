@extends('layouts.a-layout')

@section('title', 'Информация об авторе')
@section('header', 'Информация об авторе')
@section('page-title', 'Информация об авторе')

@section('breadcrumbs')
    <nav class="text-sm text-gray-500 mb-4">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('a-panel.dashboard') }}" class="text-indigo-600 hover:text-indigo-900">Панель управления</a>
                <svg class="h-5 w-5 text-gray-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('a-panel.authors') }}" class="text-indigo-600 hover:text-indigo-900">Авторы</a>
                <svg class="h-5 w-5 text-gray-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li>
                <span>{{ $author->first_name }} {{ $author->last_name }}</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-start">
                <div class="md:w-1/4 mb-4 md:mb-0 flex justify-center md:justify-start">
                    @if($author->avatar_url && filter_var($author->avatar_url, FILTER_VALIDATE_URL))
                        <img src="{{ $author->avatar_url }}" alt="{{ $author->first_name }} {{ $author->last_name }}" class="h-48 w-48 object-cover rounded-lg shadow" onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}'; this.classList.add('bg-gray-200');">
                    @else
                        <div class="h-48 w-48 rounded-lg bg-gray-200 flex items-center justify-center shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="md:w-3/4 md:pl-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $author->first_name }} {{ $author->last_name }}</h2>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">ID</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $author->id }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Рейтинг</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $author->rank }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Количество книг</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $author->books_count }} {{ trans_choice('книга|книги|книг', $author->books_count) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Дата добавления</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $author->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-3">
                        <a href="{{ route('a-panel.authors.edit', $author->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Редактировать
                        </a>

                        <form action="{{ route('a-panel.authors.destroy', $author->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этого автора?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Книги автора</h3>

            @if($author->books_count > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($author->books as $book)
                        <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                @if($book->cover_url)
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $book->title }}</h4>
                                <p class="text-xs text-gray-500 mb-2">Цена: {{ number_format($book->price, 2) }} ₽</p>
                                <a href="{{ route('a-panel.books.show', $book->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900">Подробнее →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-500 text-center py-6">
                    У этого автора пока нет книг
                </div>
            @endif
        </div>
    </div>
@endsection
