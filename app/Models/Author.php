<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * @param string|null $search Поисковый запрос
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getFilteredAuthors($search = null)
    {
        $query = static::withCount('books');

        if (!empty($search)) {
            $searchTerms = explode(' ', strtolower($search));

            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function($subq) use ($term) {
                        $subq->whereRaw('LOWER(first_name) LIKE ?', ['%' . $term . '%'])
                             ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $term . '%']);
                    });
                }
            });
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Загрузить автора с количеством книг
     *
     * @param int $id ID автора
     * @return Author
     */
    public static function getAuthorWithBooksCount($id)
    {
        return static::withCount('books')->findOrFail($id);
    }
}
