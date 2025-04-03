<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookApiController extends Controller
{
    /**
     * Сервис для работы с книгами
     *
     * @var BookService
     */
    protected $bookService;

    /**
     * Создать новый экземпляр контроллера
     *
     * @param BookService $bookService
     * @return void
     */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Получить список книг в продаже
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
            'total_count' => $books->count()
        ];

        return response()->json([
            'status' => 'success',
            'data' => BookResource::collection($books),
            'meta' => $meta,
        ]);
    }

    /**
     * Получить детальную информацию о книге
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
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
}
