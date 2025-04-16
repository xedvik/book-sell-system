<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Author;
use App\Models\Book;
use App\Models\Sell;
use App\Models\SpaClient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $spaClients = [];
        for ($i = 0; $i < 3; $i++) {
            $spaClients[] = SpaClient::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
        }
        $spaClients = collect($spaClients);

        $authors = Author::factory(20)->create();

        $books = Book::factory(50)->create()->each(function ($book) use ($authors) {

            $book->authors()->attach(
                $authors->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        foreach (range(1, 100) as $i) {
            $book = $books->random();
            $spaClient = $spaClients->random();

            Sell::create([
                'book_id' => $book->id,
                'client_id' => $spaClient->id,
                'price' => $book->price * rand(1, 3),
            ]);
        }
    }
}
