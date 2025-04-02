@extends('layouts.a-layout')

@section('title', 'Редактирование автора')
@section('header', 'Редактирование автора')
@section('page-title', 'Редактирование автора')

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
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('a-panel.authors.update', $author->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="first_name" class="block text-sm font-medium text-gray-700">Имя</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $author->first_name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('first_name') border-red-500 @enderror" required>
                    @error('first_name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Фамилия</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $author->last_name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('last_name') border-red-500 @enderror" required>
                    @error('last_name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="rank" class="block text-sm font-medium text-gray-700">Рейтинг</label>
                    <input type="number" name="rank" id="rank" value="{{ old('rank', $author->rank) }}" min="0" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('rank') border-red-500 @enderror">
                    @error('rank')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="avatar_url" class="block text-sm font-medium text-gray-700">Аватар (URL)</label>

                    @if($author->avatar_url)
                        <div class="mt-2 mb-2">
                            <img src="{{ $author->avatar_url }}" alt="{{ $author->first_name }} {{ $author->last_name }}" class="h-20 w-20 object-cover rounded">
                        </div>
                    @endif

                    <input type="url" name="avatar_url" id="avatar_url" value="{{ old('avatar_url', $author->avatar_url) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('avatar_url') border-red-500 @enderror">
                    <p class="text-gray-500 text-xs mt-1">Введите URL-адрес изображения</p>
                    @error('avatar_url')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('a-panel.authors') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                        Отмена
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Обновить
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
