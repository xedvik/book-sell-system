<?php

namespace App\Events;

use App\Models\Book;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBookAdded implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('books'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'book.added';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->book->id,
            'title' => $this->book->title,
            'description' => $this->book->description,
            'price' => $this->book->price,
            'cover_url' => $this->book->cover_url,
            'created_at' => $this->book->created_at->format('Y-m-d H:i:s'),
            'authors' => $this->book->authors->map(function ($author) {
                return [
                    'id' => $author->id,
                    'full_name' => $author->first_name . ' ' . $author->last_name,
                ];
            }),
        ];
    }
}
