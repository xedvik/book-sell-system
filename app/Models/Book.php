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
        'quantity',
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

    /**
     * Получить список книг с фильтрацией и сортировкой
     *
     * @param string|null $search Поисковый запрос
     * @param string|null $sort Тип сортировки
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getFilteredBooks($search = null, $sort = null)
    {
        $query = static::with('authors');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'quantity_asc':
                $query->orderBy('quantity', 'asc');
                break;
            case 'quantity_desc':
                $query->orderBy('quantity', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        return $query->paginate(10);
    }
}
