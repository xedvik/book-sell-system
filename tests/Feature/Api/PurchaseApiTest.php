<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseApiTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected Book $availableBook;
    protected Book $limitedBook;
    protected Book $unavailableBook;
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->availableBook = Book::factory()->create([
            'title' => 'Доступная книга',
            'price' => 1000.00,
            'quantity' => 10,
        ]);

        $this->limitedBook = Book::factory()->create([
            'title' => 'Книга с ограниченным количеством',
            'price' => 1500.00,
            'quantity' => 1,
        ]);

        $this->unavailableBook = Book::factory()->create([
            'title' => 'Недоступная книга',
            'price' => 2000.00,
            'quantity' => 0,
        ]);
    }

    /**
     * Тест успешной покупки книги
     */
    public function test_successful_book_purchase(): void
    {
        $response = $this->postJson('/api/books/' . $this->availableBook->id . '/purchase', [
            'user_id' => $this->user->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'sale_id',
                    'book',
                    'quantity',
                    'total_price',
                    'sale'
                ]
            ]);


        $this->assertEquals(9, Book::find($this->availableBook->id)->quantity);


        $this->assertDatabaseHas('sells', [
            'book_id' => $this->availableBook->id,
            'client_id' => $this->user->id,
            'price' => 1000.00,
        ]);
    }

    /**
     * Тест покупки нескольких экземпляров книги
     */
    public function test_purchase_multiple_copies(): void
    {
        $response = $this->postJson('/api/books/' . $this->availableBook->id . '/purchase', [
            'user_id' => $this->user->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.quantity', 3)
            ->assertJsonPath('data.total_price', 3000);


        $this->assertEquals(7, Book::find($this->availableBook->id)->quantity);
    }

    /**
     * Тест покупки книги с ограниченным количеством
     */
    public function test_purchase_limited_book(): void
    {
        $response = $this->postJson('/api/books/' . $this->limitedBook->id . '/purchase', [
            'user_id' => $this->user->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');


        $this->assertEquals(0, Book::find($this->limitedBook->id)->quantity);
    }

    /**
     * Тест покупки недоступной книги
     */
    public function test_purchase_unavailable_book(): void
    {
        $response = $this->postJson('/api/books/' . $this->unavailableBook->id . '/purchase', [
            'user_id' => $this->user->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(400)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Недостаточное количество книг в наличии');
    }

    /**
     * Тест покупки с недостаточным количеством
     */
    public function test_purchase_insufficient_quantity(): void
    {
        $response = $this->postJson('/api/books/' . $this->availableBook->id . '/purchase', [
            'user_id' => $this->user->id,
            'quantity' => 20,
        ]);

        $response->assertStatus(400)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Недостаточное количество книг в наличии');
    }

    /**
     * Тест покупки несуществующей книги
     */
    public function test_purchase_nonexistent_book(): void
    {
        $response = $this->postJson('/api/books/9999/purchase', [
            'user_id' => $this->user->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(400)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Книга не найдена');
    }

    /**
     * Тест покупки без указания пользователя
     */
    public function test_purchase_without_user(): void
    {
        $response = $this->postJson('/api/books/' . $this->availableBook->id . '/purchase', [
            'quantity' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    'user_id'
                ]
            ]);
    }

    /**
     * Тест покупки с некорректным количеством
     */
    public function test_purchase_with_invalid_quantity(): void
    {
        $response = $this->postJson('/api/books/' . $this->availableBook->id . '/purchase', [
            'user_id' => $this->user->id,
            'quantity' => -1,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    'quantity'
                ]
            ]);
    }
}
