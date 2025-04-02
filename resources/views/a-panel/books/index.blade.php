@extends('layouts.a-layout')

@section('title', 'Управление книгами')
@section('header', 'Управление книгами')
@section('page-title', 'Управление книгами')

@section('breadcrumbs')
    <nav class="text-sm text-gray-500 mb-4">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('a-panel.dashboard') }}" class="text-indigo-600 hover:text-indigo-900">Панель управления</a>
                <svg class="h-5 w-5 text-gray-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li>
                <span>Книги</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <form action="{{ route('a-panel.books') }}" method="GET" class="flex flex-col">
                <div class="flex space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Поиск книг..." class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Найти
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Поиск выполняется по названию и описанию книги</p>
            </form>

            <div>
                <select name="sort" id="sort" onchange="window.location.href = this.value" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="{{ route('a-panel.books') }}" {{ !request('sort') ? 'selected' : '' }}>Сначала новые</option>
                    <option value="{{ route('a-panel.books', ['sort' => 'price_asc', 'search' => request('search')]) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена (по возрастанию)</option>
                    <option value="{{ route('a-panel.books', ['sort' => 'price_desc', 'search' => request('search')]) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена (по убыванию)</option>
                    <option value="{{ route('a-panel.books', ['sort' => 'quantity_asc', 'search' => request('search')]) }}" {{ request('sort') == 'quantity_asc' ? 'selected' : '' }}>Количество (по возрастанию)</option>
                    <option value="{{ route('a-panel.books', ['sort' => 'quantity_desc', 'search' => request('search')]) }}" {{ request('sort') == 'quantity_desc' ? 'selected' : '' }}>Количество (по убыванию)</option>
                </select>
            </div>
        </div>

        <a href="{{ route('a-panel.books.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Добавить книгу
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Обложка</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Авторы</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Остаток</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата добавления</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($books ?? [] as $book)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($book->cover_url && filter_var($book->cover_url, FILTER_VALIDATE_URL))
                                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="h-12 w-10 object-cover rounded" onerror="this.onerror=null; this.src='{{ asset('images/book.jpg') }}'; this.classList.add('bg-gray-200');">
                                @else
                                    <div class="h-12 w-10 bg-gray-200 rounded flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $book->title }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($book->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($book->authors && $book->authors->count() > 0)
                                    <div class="flex flex-col space-y-1">
                                        @foreach($book->authors->take(2) as $author)
                                            <span class="text-xs px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded-full">
                                                {{ $author->first_name }} {{ $author->last_name }}
                                            </span>
                                        @endforeach
                                        @if($book->authors->count() > 2)
                                            <span class="text-xs text-gray-500">+{{ $book->authors->count() - 2 }} ещё</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">Нет автора</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($book->price, 2) }} ₽</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($book->quantity > 10)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $book->quantity }} шт.</span>
                                @elseif($book->quantity > 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $book->quantity }} шт.</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Нет в наличии</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->created_at->format('d.m.Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('a-panel.books.edit', $book->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Редактировать">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('a-panel.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту книгу?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Удалить">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Книги не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <div class="mt-4">
        {{ $books->links() ?? '' }}
    </div>

    <div x-data="{ open: false }" x-on:keydown.escape.prevent.stop="open = false" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        // Здесь может быть JavaScript для AJAX операций, модальных окон и т.д.
    });
</script>
@endpush
