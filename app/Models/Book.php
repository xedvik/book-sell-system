<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cover_url',
        'price',
        'quantity'
    ];


    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book')
            ->withTimestamps();
    }

    public function sells(): HasMany
    {
        return $this->hasMany(Sell::class);
    }
}
