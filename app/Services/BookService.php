<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BookService
{
    /**
     * Получить список книг в продаже с фильтрацией и сортировкой
     *
     * @param  Request  $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableBooks(Request $request)
    {
        $sortField = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $filters = $this->getFiltersFromRequest($request);

        return Book::getAvailableBooks(
            $filters,
            $sortField,
            $sortDirection
        );
    }

    /**
     * Получить детальную информацию о книге
     *
     * @param  int  $id
     * @return Book|null
     */
    public function getBookDetails(int $id): ?Book
    {
        return Book::with('authors')->find($id);
    }

    /**
     * Проверить, есть ли книга в наличии
     *
     * @param  Book|null  $book
     * @return bool
     */
    public function isBookAvailable(?Book $book): bool
    {
        return $book && $book->quantity > 0;
    }

    /**
     * Формируем массив фильтров из запроса
     *
     * @param  Request  $request
     * @return array
     */
    private function getFiltersFromRequest(Request $request): array
    {
        $filters = [];

        $possibleFilters = [
            'title',
            'description',
            'min_price',
            'max_price',
            'min_quantity'
        ];

        foreach ($possibleFilters as $filter) {
            if ($request->has($filter)) {
                $filters[$filter] = $request->input($filter);
            }
        }

        return $filters;
    }

    /**
     * Получить фильтры из запроса для мета-информации
     *
     * @param  Request  $request
     * @return array
     */
    public function getRequestFilters(Request $request): array
    {
        return $this->getFiltersFromRequest($request);
    }
}
