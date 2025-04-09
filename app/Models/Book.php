<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
        $query = static::with('authors')->where('quantity', '>', 0);;

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%");
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

        return $query->paginate(config('pagination.per_page', 10));
    }

    /**
     * Получить список книг в продаже с возможностью фильтрации и сортировки
     *
     * @param array $filters
     * @param string|null $sortField
     * @param string $sortDirection
     * @param bool $withAuthorAvatar
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAvailableBooks(array $filters = [], $sortField = null, $sortDirection = 'asc', $withAuthorAvatar = false)
    {
        $query = static::with('authors')->withCount('sells')->where('quantity', '>', 0);

        if (!empty($filters)) {
            static::applyCommonFilters($query, $filters);
        }

        if ($withAuthorAvatar) {
            $query->whereHas('authors', function ($q) {
                $q->whereNotNull('avatar_url')
                    ->where('avatar_url', '!=', '');
            });
        }

        if ($sortField) {
            $allowedSortFields = ['id', 'title', 'price', 'quantity', 'created_at', 'sells_count'];
            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
            }
        } else {
            $query->latest('id');
        }

        return $query->get();
    }

    /**
     * Получить книги авторов с высоким рейтингом или с большим количеством продаж за сегодня
     *
     * @param array $filters
     * @param string|null $sortField
     * @param string $sortDirection
     * @param int $minAuthorRank
     * @param int $minTodaySales
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getBooksHighRankOrTopSales(array $filters = [], $sortField = null, $sortDirection = 'asc', $minAuthorRank = 75, $minTodaySales = 3)
    {
        $query = static::with('authors')
            ->withCount('sells')
            ->where('quantity', '>', 0);

        if (!empty($filters)) {
            static::applyCommonFilters($query, $filters);
        }

        $query->where(function ($q) use ($minAuthorRank, $minTodaySales) {

            $q->whereHas('authors', function ($authorQuery) use ($minAuthorRank) {
                $authorQuery->where('rank', '>', $minAuthorRank);
            });

            $q->orWhereRaw('(SELECT COUNT(*) FROM sells WHERE sells.book_id = books.id AND DATE(sells.created_at) = ?) > ?', [now()->toDateString(), $minTodaySales]);
        });

        if ($sortField) {
            $allowedSortFields = ['id', 'title', 'price', 'quantity', 'created_at', 'sells_count'];
            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
            }
        } else {
            $query->latest('id');
        }

        return $query->get();
    }

    /**
     * Применить общие фильтры к запросу
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    private static function applyCommonFilters($query, array $filters = [])
    {

        if (isset($filters['title'])) {
            $query->where('title', 'ILIKE', "%{$filters['title']}%");
        }

        if (isset($filters['description'])) {
            $query->where('description', 'ILIKE', "%{$filters['description']}%");
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

    /**
     * Очистить кэш, связанный с книгами
     */
    public static function invalidateCache()
    {
        // Очищаем все кэши, связанные с книгами
        Cache::forget('books');
        Cache::forget('booksCount');
        Cache::forget('latestBooks');

        // Паттерн-очистка для динамических ключей
        $patterns = [
            'books:available:*',
            'books:top:*',
            'books:page:*',
            'book:*',
            'book-*'
        ];

        foreach ($patterns as $pattern) {
            $keys = Cache::get('cache_keys:' . $pattern, []);
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        }
    }
}
