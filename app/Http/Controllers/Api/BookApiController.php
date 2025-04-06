<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\SellResource;
use App\Services\BookService;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Book Store API",
 *     version="1.0.0",
 *     description="API для работы с книгами в интернет-магазине"
 * )
 * @OA\Server(
 *     url="http:///localhost:8000/api",
 *     description="Production server"
 * )
 * @OA\Tag(
 *     name="Books",
 *     description="Операции с книгами"
 * )
 */
class BookApiController extends Controller
{
    protected $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
     /**
     * @OA\Get(
     *     path="/books",
     *     tags={"Books"},
     *     summary="Получить список книг в продаже",
     *     description="Возвращает отфильтрованный и отсортированный список книг",
     *     @OA\Parameter(
 *         name="sort_by",
 *         in="query",
 *         description="Поле для сортировки (доступные значения: id, title - название, price - цена, quantity - количество, created_at - дата создания, sells_count - количество продаж)",
 *         @OA\Schema(
 *             type="string",
 *             enum={"id", "title", "price", "quantity", "created_at", "sells_count"},
 *             default="id"
 *         )
 *     ),
     *     @OA\Parameter(name="sort_direction", in="query", @OA\Schema(type="string", enum={"asc", "desc"}, default="asc")),
     *     @OA\Parameter(name="title", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="min_price", in="query", @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="max_price", in="query", @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="min_quantity", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="with_author_avatar", in="query", @OA\Schema(type="boolean", default=false), description="Показать только книги авторов с аватарками"),
     *     @OA\Response(response=200, description="Успешный запрос", @OA\JsonContent(ref="#/components/schemas/BooksListResponse"))
     * )
     */
    public function index(Request $request)
    {
        $books = $this->bookService->getAvailableBooks($request);

        $meta = [
            'filters' => $this->bookService->getRequestFilters($request),
            'sort' => [
                'field' => $request->input('sort_by'),
                'direction' => $request->input('sort_direction', 'asc'),
            ],
            'additional_filters' => [
                'with_author_avatar' => $request->has('with_author_avatar') ? (bool)$request->input('with_author_avatar') : false,
            ],
            'total_count' => $books->count()
        ];

        return response()->json([
            'status' => 'success',
            'data' => BookResource::collection($books),
            'meta' => $meta,
        ]);
    }

     /**
     * @OA\Get(
     *     path="/books/{id}",
     *     tags={"Books"},
     *     summary="Получить детальную информацию о книге",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Успешный запрос", @OA\JsonContent(ref="#/components/schemas/BookDetailResponse")),
     *     @OA\Response(response=404, description="Книга не найдена", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    public function show($id)
    {
        $book = $this->bookService->getBookDetails($id);

        if (!$this->bookService->isBookAvailable($book)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Книга не найдена или отсутствует в продаже'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new BookResource($book)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/books/{id}/purchase",
     *     tags={"Books"},
     *     summary="Купить книгу",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", example=1, description="ID клиента из таблицы spa_clients"),
     *             @OA\Property(property="quantity", type="integer", example=1, default=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Книга успешно куплена", @OA\JsonContent(ref="#/components/schemas/PurchaseResponse")),
     *     @OA\Response(response=400, description="Ошибка при покупке", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *     @OA\Response(response=404, description="Книга не найдена", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    public function purchase(\App\Http\Requests\Api\PurchaseBookRequest $request, $id)
    {
        $userId = $request->input('user_id');
        $quantity = $request->input('quantity', 1);

        $result = $this->bookService->purchaseBook($id, $userId, $quantity);

        if (!$result['success']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
                'data' => isset($result['available']) ? ['available' => $result['available']] : null
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
            'data' => [
                'sale_id' => $result['sale_id'],
                'book' => new BookResource($result['book']),
                'quantity' => $result['quantity'],
                'total_price' => $result['total_price'],
                'sale' => new SellResource($result['sell'])
            ]
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/books/top",
     *     tags={"Books"},
     *     summary="Получить список книг авторов с высоким рейтингом или с высокими продажами за сегодня",
     *     description="Возвращает список книг у авторов которых рейтинг выше 75 либо количество продаж за текущий день больше 3",
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Поле для сортировки (доступные значения: id, title - название, price - цена, quantity - количество, created_at - дата создания, sells_count - количество продаж)",
     *         @OA\Schema(
     *             type="string",
     *             enum={"id", "title", "price", "quantity", "created_at", "sells_count"},
     *             default="id"
     *         )
     *     ),
     *     @OA\Parameter(name="sort_direction", in="query", @OA\Schema(type="string", enum={"asc", "desc"}, default="asc")),
     *     @OA\Parameter(name="title", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="min_price", in="query", @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="max_price", in="query", @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="min_quantity", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="min_author_rank", in="query", @OA\Schema(type="integer", default=75)),
     *     @OA\Parameter(name="min_today_sales", in="query", @OA\Schema(type="integer", default=3)),
     *     @OA\Response(response=200, description="Успешный запрос", @OA\JsonContent(ref="#/components/schemas/BooksListResponse"))
     * )
     */
    public function topBooks(Request $request)
    {
        $books = $this->bookService->getBooksHighRankOrTopSales($request);

        $meta = [
            'filters' => $this->bookService->getRequestFilters($request),
            'sort' => [
                'field' => $request->input('sort_by'),
                'direction' => $request->input('sort_direction', 'asc'),
            ],
            'additional_filters' => [
                'min_author_rank' => $request->input('min_author_rank', 75),
                'min_today_sales' => $request->input('min_today_sales', 3),
            ],
            'total_count' => $books->count()
        ];

        return response()->json([
            'status' => 'success',
            'data' => BookResource::collection($books),
            'meta' => $meta,
        ]);
    }
}
