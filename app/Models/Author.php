<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Author extends Model
{
    use HasFactory;


    protected $fillable = [
        'first_name',
        'last_name',
        'rank',
        'avatar_url',
    ];


    public function books()
    {
        return $this->belongsToMany(Book::class, 'author_book')
            ->withTimestamps();
    }

    /**
     * Получить список авторов с фильтрацией
     *
     * @param string|null $search
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getFilteredAuthors($search = null)
    {
        $query = static::withCount('books');

        if (!empty($search)) {
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($subq) use ($term) {
                        $subq->where('first_name', 'ILIKE', '%' . $term . '%')
                            ->orWhere('last_name', 'ILIKE', '%' . $term . '%');
                    });
                }
            });
        }

        return $query->latest()->paginate(config('pagination.per_page', 10));
    }

    /**
     * Загрузить автора с количеством книг
     *
     * @param int $id
     * @return Author
     */
    public static function getAuthorWithBooksCount($id)
    {
        return static::withCount('books')->findOrFail($id);
    }

    /**
     * Модель загружена
     */
    protected static function booted()
    {

        static::saved(function ($author) {
            static::invalidateCache();
            Book::invalidateCache();
        });


        static::deleted(function ($author) {
            static::invalidateCache();
            Book::invalidateCache();
        });
    }

    /**
     * Очистить кэш, связанный с авторами
     */
    public static function invalidateCache()
    {

        Cache::forget('authors');
        Cache::forget('authorsCount');


        $patterns = ['author-*'];

        foreach ($patterns as $pattern) {
            $keys = Cache::get('cache_keys:' . $pattern, []);
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        }
    }
}
