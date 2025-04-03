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
     * @param string|null $search
     * @param string|null $sort
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

    /**
     * Получить список книг в продаже с возможностью фильтрации и сортировки
     *
     * @param array $filters
     * @param string|null $sortField
     * @param string $sortDirection
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAvailableBooks(array $filters = [], $sortField = null, $sortDirection = 'asc')
    {
        $query = static::with('authors')->where('quantity', '>', 0);

        if (!empty($filters)) {
            if (isset($filters['title'])) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($filters['title']) . '%']);
            }

            if (isset($filters['description'])) {
                $query->whereRaw('LOWER(description) LIKE ?', ['%' . strtolower($filters['description']) . '%']);
            }

            if (isset($filters['min_price'])) {
                $query->where('price', '>=', $filters['min_price']);
            }

            if (isset($filters['max_price'])) {
                $query->where('price', '<=', $filters['max_price']);
            }

            if (isset($filters['min_quantity'])) {
                $query->where('quantity', '>=', $filters['min_quantity']);
            }
        }

        if ($sortField) {
            $allowedSortFields = ['id', 'title', 'price', 'quantity', 'created_at'];
            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
            }
        } else {
            $query->latest('id');
        }

        return $query->get();
    }
}
