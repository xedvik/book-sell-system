@extends('layouts.a-layout')

@section('title', 'Панель управления')
@section('header', 'Панель управления')
@section('page-title', 'Панель управления')

@section('breadcrumbs')
    <nav class="text-sm text-gray-500 mb-4">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <span>Панель управления</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0 rounded-md bg-indigo-500 p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Книги</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $booksCount ?? 0 }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <a href="{{ route('a-panel.books') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Перейти к списку книг</a>
            </div>
        </div>


        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0 rounded-md bg-green-500 p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Авторы</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $authorsCount ?? 0 }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <a href="{{ route('a-panel.authors') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Перейти к списку авторов</a>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0 rounded-md bg-blue-500 p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Пользователи</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $usersCount ?? 0 }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <a href="{{ route('a-panel.users') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Перейти к списку пользователей</a>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <div class="flex-shrink-0 rounded-md bg-yellow-500 p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Продажи</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $salesCount ?? 0 }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <a href="{{ route('a-panel.sales') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Перейти к списку продаж</a>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-3">Последние добавленные книги</h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($latestBooks ?? [] as $book)
                    <li>
                        <a href="{{ route('a-panel.books.edit', $book->id) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">{{ $book->title }}</p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $book->price }} ₽
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                            @if($book->authors && $book->authors->count() > 0)
                                                {{ $book->authors->first()->first_name }} {{ $book->authors->first()->last_name }}
                                                @if($book->authors->count() > 1)
                                                    <span class="text-xs text-gray-500 ml-1">(+{{ $book->authors->count() - 1 }})</span>
                                                @endif
                                            @else
                                                Без автора
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        <p>
                                            Добавлена {{ $book->created_at->format('d.m.Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6 text-gray-500 text-center">
                        Книги еще не добавлены
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <div>
        <h2 class="text-lg font-medium text-gray-900 mb-3">Последние продажи</h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($latestSales ?? [] as $sale)
                    <li>
                        <a href="{{ route('a-panel.sales.show', $sale->id) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        Заказ #{{ $sale->id }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ number_format($sale->price, 2) }} ₽
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $sale->client->name ?? 'Неизвестный пользователь' }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        <p>
                                            {{ $sale->created_at->format('d.m.Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6 text-gray-500 text-center">
                        Продаж еще не было
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
