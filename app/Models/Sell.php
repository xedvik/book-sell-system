<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sell extends Model
{
    use HasFactory;


    protected $fillable = [
        'book_id',
        'client_id',
        'price'
    ];


    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }


    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
