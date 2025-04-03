<?php

namespace Tests\Feature\Api;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Настройка тестовых данных
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Создаем авторов для тестов
        $author1 = Author::factory()->create([
            'first_name' => 'Александр',
            'last_name' => 'Пушкин',
            'rank' => Faker::create()->numberBetween(1, 100),
        ]);

        $author2 = Author::factory()->create([
            'first_name' => 'Федор',
            'last_name' => 'Достоевский',
            'rank' => Faker::create()->numberBetween(1, 100),
        ]);

        $book1 = Book::factory()->create([
            'title' => 'Евгений Онегин',
            'description' => 'Роман в стихах',
            'price' => 500.00,
            'quantity' => 10,
        ]);

        $book2 = Book::factory()->create([
            'title' => 'Преступление и наказание',
            'description' => 'Философский роман',
            'price' => 650.00,
            'quantity' => 5,
        ]);

        $book3 = Book::factory()->create([
            'title' => 'Капитанская дочка',
            'description' => 'Исторический роман',
            'price' => 450.00,
            'quantity' => 0,
        ]);


        $book1->authors()->attach($author1->id);
        $book2->authors()->attach($author2->id);
        $book3->authors()->attach($author1->id);
    }

    /**
     * Тест получения списка книг в продаже
     */
    public function test_get_available_books(): void
    {
        $response = $this->getJson('/api/books');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'price',
                        'quantity',
                        'in_stock',
                        'authors',
                    ]
                ],
                'meta' => [
                    'filters',
                    'sort',
                    'total_count'
                ]
            ]);
    }

    /**
     * Тест фильтрации книг по названию
     */
    public function test_filter_books_by_title(): void
    {
        $response = $this->getJson('/api/books?title=Евгений Онегин');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Евгений Онегин');
    }

    /**
     * Тест сортировки книг по цене (по возрастанию)
     */
    public function test_sort_books_by_price_asc(): void
    {
        $response = $this->getJson('/api/books?sort_by=price&sort_direction=asc');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.0.title', 'Евгений Онегин')
            ->assertJsonPath('data.1.title', 'Преступление и наказание');
    }

    /**
     * Тест сортировки книг по цене (по убыванию)
     */
    public function test_sort_books_by_price_desc(): void
    {
        $response = $this->getJson('/api/books?sort_by=price&sort_direction=desc');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.0.title', 'Преступление и наказание')
            ->assertJsonPath('data.1.title', 'Евгений Онегин');
    }

    /**
     * Тест получения информации о конкретной книге
     */
    public function test_get_book_details(): void
    {
        $book = Book::where('quantity', '>', 0)->first();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.id', $book->id)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'price',
                    'quantity',
                    'in_stock',
                    'authors',
                ]
            ]);
    }

    /**
     * Тест получения информации о несуществующей книге
     */
    public function test_get_nonexistent_book(): void
    {
        $response = $this->getJson('/api/books/9999');

        $response->assertStatus(404)
            ->assertJsonPath('status', 'error');
    }

    /**
     * Тест получения информации о книге, которой нет в наличии
     */
    public function test_get_out_of_stock_book(): void
    {
        $book = Book::where('quantity', 0)->first();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(404)
            ->assertJsonPath('status', 'error');
    }
}
