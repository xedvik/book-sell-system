<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Author;
use App\Models\Book;
use App\Models\Sell;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем 10 пользователей
        $users = User::factory(10)->create();

        // Создаем 20 авторов
        $authors = Author::factory(20)->create();

        // Создаем 50 книг и связываем с авторами
        $books = Book::factory(50)->create()->each(function ($book) use ($authors) {
            // Каждая книга имеет от 1 до 3 авторов
            $book->authors()->attach(
                $authors->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        foreach (range(1, 100) as $i) {
            $book = $books->random();
            $user = $users->random();

            Sell::create([
                'book_id' => $book->id,
                'client_id' => $user->id,
                'price' => $book->price * rand(1, 3)
            ]);
        }
    }
}
