@extends('layouts.a-layout')

@section('title', 'Создание продажи')
@section('header', 'Создание продажи')
@section('page-title', 'Создание продажи')

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
                <span>Создание</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('a-panel.sales.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <h3 class="text-lg font-medium text-gray-900">Информация о продаже</h3>
                    <p class="mt-1 text-sm text-gray-500">Заполните информацию о новой продаже.</p>
                </div>

                <div class="grid grid-cols-6 gap-6">

                    <div class="col-span-6 sm:col-span-3">
                        <label for="client_id" class="block text-sm font-medium text-gray-700">Клиент</label>
                        <select id="client_id" name="client_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Выберите клиента</option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} ({{ $client->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="col-span-6 sm:col-span-3">
                        <label for="book_id" class="block text-sm font-medium text-gray-700">Книга</label>
                        <select id="book_id" name="book_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Выберите книгу</option>
                            @foreach($books ?? [] as $book)
                                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} ({{ number_format($book->price, 2) }} ₽)
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="col-span-6 sm:col-span-2">
                        <label for="price" class="block text-sm font-medium text-gray-700">Цена (₽)</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('a-panel.sales') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Отмена
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Создать продажу
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookSelect = document.getElementById('book_id');
        const priceInput = document.getElementById('price');

        // Автозаполнение цены при выборе книги
        bookSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const bookPrice = selectedOption.textContent.match(/\(([\d.,]+) ₽\)/);

            if (bookPrice && bookPrice[1]) {
                priceInput.value = parseFloat(bookPrice[1].replace(',', ''));
            }
        });
    });
</script>
@endpush
