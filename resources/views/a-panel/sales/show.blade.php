@extends('layouts.a-layout')

@section('title', 'Просмотр продажи')
@section('header', 'Просмотр продажи')
@section('page-title', 'Просмотр продажи #' . $sell->id)

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
                <a href="{{ route('a-panel.sales') }}" class="text-indigo-600 hover:text-indigo-900">Продажи</a>
                <svg class="h-5 w-5 text-gray-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li>
                <span>Просмотр #{{ $sell->id }}</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Информация о продаже #{{ $sell->id }}</h3>
                    <p class="mt-1 text-sm text-gray-500">Дата создания: {{ $sell->created_at->format('d.m.Y H:i') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('a-panel.sales.edit', $sell->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Редактировать
                    </a>
                    <form action="{{ route('a-panel.sales.destroy', $sell->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту продажу?');">
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

            <div class="mt-6 border-t border-gray-200 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Информация о книге</h4>
                        <div class="mt-2 flex items-center">
                            <div class="flex-shrink-0 h-16 w-12 bg-gray-100 rounded overflow-hidden">
                                @if($sell->book && $sell->book->cover_url)
                                    <img src="{{  $sell->book->cover_url }}" alt="{{ $sell->book->title }}" class="h-full w-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h5 class="text-sm font-medium text-gray-900">{{ $sell->book->title ?? 'Неизвестная книга' }}</h5>
                                <p class="text-sm text-gray-500">ID: {{ $sell->book_id }}</p>
                                @if($sell->book)
                                    <a href="{{ route('a-panel.books.show', $sell->book->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900">Подробнее о книге →</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Информация о клиенте</h4>
                        <div class="mt-2 flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($sell->client->name ?? 'Неизвестно') }}&color=7F9CF5&background=EBF4FF" alt="{{ $sell->client->name ?? 'Неизвестно' }}">
                            </div>
                            <div class="ml-4">
                                <h5 class="text-sm font-medium text-gray-900">{{ $sell->client->name ?? 'Неизвестный клиент' }}</h5>
                                <p class="text-sm text-gray-500">ID: {{ $sell->client_id }}</p>
                                @if($sell->client)
                                    <p class="text-sm text-gray-500">{{ $sell->client->email }}</p>
                                    <a href="{{ route('a-panel.spa-clients.show', $sell->client->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900">Подробнее о клиенте →</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h4 class="text-sm font-medium text-gray-900">Детали продажи</h4>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Цена:</span>
                            <span class="text-sm font-medium text-gray-900 ml-2">{{ number_format($sell->price, 2) }} ₽</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Дата создания:</span>
                            <span class="text-sm font-medium text-gray-900 ml-2">{{ $sell->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Дата обновления:</span>
                            <span class="text-sm font-medium text-gray-900 ml-2">{{ $sell->updated_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-start">
        <a href="{{ route('a-panel.sales') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Вернуться к списку
        </a>
    </div>
@endsection
