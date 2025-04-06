<?php

namespace App\Http\Swagger;

/**
 * Основные схемы для API книжного магазина
 */

/**
 * @OA\Schema(
 *     schema="Book",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Название книги"),
 *     @OA\Property(property="description", type="string", example="Описание книги"),
 *     @OA\Property(property="cover_url", type="string", example="https://example.com/cover.jpg"),
 *     @OA\Property(property="price", type="number", format="float", example=19.99),
 *     @OA\Property(property="quantity", type="integer", example=10),
 *     @OA\Property(
 *         property="authors",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Author")
 *     ),
 *     @OA\Property(property="sells_count", type="integer", example=15, description="Количество продаж книги"),
 *     @OA\Property(property="in_stock", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Author",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="first_name", type="string", example="Иван"),
 *     @OA\Property(property="last_name", type="string", example="Иванов"),
 *     @OA\Property(property="full_name", type="string", example="Иван Иванов"),
 *     @OA\Property(property="rank", type="integer", example=5),
 *     @OA\Property(property="avatar_url", type="string", example="https://example.com/avatar.jpg"),
 *     @OA\Property(property="books_count", type="integer", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Sale",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="book", ref="#/components/schemas/Book"),
 *     @OA\Property(property="client_id", type="integer", example=1),
 *     @OA\Property(property="price", type="number", format="float", example=19.99),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="BooksListResponse",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="success"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Book")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="filters", type="object"),
 *         @OA\Property(
 *             property="sort",
 *             type="object",
 *             @OA\Property(property="field", type="string", nullable=true),
 *             @OA\Property(property="direction", type="string")
 *         ),
 *         @OA\Property(property="total_count", type="integer")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BookDetailResponse",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="success"),
 *     @OA\Property(property="data", ref="#/components/schemas/Book")
 * )
 *
 * @OA\Schema(
 *     schema="PurchaseResponse",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="success"),
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(property="sale_id", type="integer"),
 *         @OA\Property(property="book", ref="#/components/schemas/Book"),
 *         @OA\Property(property="quantity", type="integer"),
 *         @OA\Property(property="total_price", type="number", format="float"),
 *         @OA\Property(property="sale", ref="#/components/schemas/Sale")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="error"),
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(property="available", type="integer"),
 *         nullable=true
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="SpaClient",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Иван Петров"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *     @OA\Property(property="phone", type="string", example="+7 (999) 123-45-67", nullable=true),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="SpaClientListResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/SpaClient")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="SpaClientResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Клиент успешно создан"),
 *     @OA\Property(property="data", ref="#/components/schemas/SpaClient")
 * )
 *
 * @OA\Schema(
 *     schema="SpaClientErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Ошибка валидации"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\Property(property="email", type="array", @OA\Items(type="string", example="Такой email уже существует"))
 *     )
 * )
 */
class Schemas
{
    // Класс существует только для аннотаций
}
