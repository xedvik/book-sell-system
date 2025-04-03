@extends('layouts.a-layout')

@section('title', 'Редактирование книги')
@section('header', 'Редактирование книги')
@section('page-title', 'Редактирование книги: '.$book->title)

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
                <a href="{{ route('a-panel.books') }}" class="text-indigo-600 hover:text-indigo-900">Книги</a>
                <svg class="h-5 w-5 text-gray-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li>
                <span>Редактирование</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <form action="{{ route('a-panel.books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Основная информация</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Измените основную информацию о книге.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Название</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <label for="authors" class="block text-sm font-medium text-gray-700">Авторы</label>
                            <select id="authors" name="authors[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" multiple>
                                @foreach($authors ?? [] as $author)
                                    <option value="{{ $author->id }}" {{ in_array($author->id, old('authors', $book->authors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $author->first_name }} {{ $author->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Удерживайте Ctrl (Cmd на Mac) для выбора нескольких авторов</p>
                            @error('authors')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="price" class="block text-sm font-medium text-gray-700">Цена (руб.)</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $book->price) }}" step="0.01" min="0" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Количество</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $book->quantity) }}" min="0" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description', $book->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Обложка</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Измените URL обложки книги.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                            <label for="cover_url" class="block text-sm font-medium text-gray-700">Обложка (URL)</label>

                            @if($book->cover_url)
                                <div class="mt-2 mb-4">
                                    <div class="flex items-center">
                                        <div class="w-20 h-28 overflow-hidden rounded border border-gray-200">
                                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm text-gray-500">Текущая обложка</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input type="text" name="cover_url" id="cover_url" value="{{ old('cover_url', $book->cover_url) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="https://example.com/image.jpg">
                            @error('cover_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('a-panel.books') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Отмена
            </a>
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Обновить
            </button>
        </div>
    </form>

    <div class="mt-6">
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Удаление книги</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Будьте осторожны, это действие нельзя отменить.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">
                                Удаление книги приведет к удалению всей связанной с ней информации.
                            </p>
                        </div>
                        <form action="{{ route('a-panel.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту книгу?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Удалить книгу
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const coverInput = document.getElementById('cover_path');

        if (coverInput) {
            coverInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Код для предварительного просмотра загруженного изображения
                        // Можно реализовать при необходимости
                    }

                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
    });
</script>
@endpush
