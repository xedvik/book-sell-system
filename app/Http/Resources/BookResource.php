<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'cover_url' => $this->cover_url,
            'price' => (float) $this->price,
            'quantity' => $this->quantity,
            'sells_count' => $this->sells_count,
            'authors' => AuthorResource::collection($this->whenLoaded('authors')),
            'in_stock' => $this->quantity > 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
