<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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

        // Добавляем фильтр по наличию аватарки у автора
        $withAuthorAvatar = $request->has('with_author_avatar') ? (bool)$request->input('with_author_avatar') : false;

        return Book::getAvailableBooks(
            $filters,
            $sortField,
            $sortDirection,
            $withAuthorAvatar
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

        // Добавляем фильтр по наличию аватарки у автора
        if ($request->has('with_author_avatar')) {
            $filters['with_author_avatar'] = (bool)$request->input('with_author_avatar');
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
        // Получаем все базовые фильтры
        $filters = $this->getFiltersFromRequest($request);

        // Исключаем специальные фильтры, которые обрабатываются отдельно
        if (isset($filters['with_author_avatar'])) {
            unset($filters['with_author_avatar']);
        }

        return $filters;
    }

    /**
     * Получить книги авторов с высоким рейтингом или с большим количеством продаж за сегодня
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBooksHighRankOrTopSales(Request $request)
    {
        $sortField = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $filters = $this->getFiltersFromRequest($request);

        $minAuthorRank = $request->input('min_author_rank', 75);
        $minTodaySales = $request->input('min_today_sales', 3);

        return Book::getBooksHighRankOrTopSales(
            $filters,
            $sortField,
            $sortDirection,
            $minAuthorRank,
            $minTodaySales
        );
    }

    /**
     * Купить книгу
     *
     * @param  int  $bookId
     * @param  int  $userId
     * @param  int  $quantity
     * @return array
     */
    public function purchaseBook(int $bookId, int $userId, int $quantity = 1): array
    {
        $result = DB::transaction(function () use ($bookId, $userId, $quantity) {
            $book = Book::lockForUpdate()->find($bookId);

            if (!$book) {
                return [
                    'success' => false,
                    'message' => 'Книга не найдена'
                ];
            }

            if ($book->quantity < $quantity) {
                return [
                    'success' => false,
                    'message' => 'Недостаточное количество книг в наличии',
                    'available' => $book->quantity
                ];
            }

            $sell = new \App\Models\Sell([
                'book_id' => $bookId,
                'client_id' => $userId,
                'price' => $book->price * $quantity
            ]);
            $sell->save();

            $sell->load('book');
            $book->quantity -= $quantity;
            $book->save();

            return [
                'success' => true,
                'message' => 'Покупка успешно совершена',
                'sale_id' => $sell->id,
                'book' => $book,
                'quantity' => $quantity,
                'total_price' => (float)$sell->price,
                'sell' => $sell
            ];
        });

        return $result;
    }
}

