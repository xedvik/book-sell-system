<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SpaClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="SpaClients",
 *     description="Операции с клиентами SPA"
 * )
 */
class SpaClientController extends Controller
{
    /**
     * Создать нового SPA клиента
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/spa-clients",
     *     tags={"SpaClients"},
     *     summary="Создать нового SPA клиента",
     *     description="Создает нового клиента SPA-приложения",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *
     *             @OA\Property(property="name", type="string", example="Иван Петров"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="phone", type="string", example="+7 (999) 123-45-67"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Клиент успешно создан",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SpaClientResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SpaClientErrorResponse")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:spa_clients',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $validator->errors(),
            ], 422);
        }

        $client = SpaClient::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Клиент успешно создан',
            'data' => $client,
        ], 201);
    }

    /**
     * Получить список всех клиентов SPA
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/spa-clients",
     *     tags={"SpaClients"},
     *     summary="Получить список всех SPA клиентов",
     *     description="Возвращает список всех клиентов SPA-приложения",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SpaClientListResponse")
     *     )
     * )
     */
    public function index()
    {
        $clients = SpaClient::all();

        return response()->json([
            'success' => true,
            'data' => $clients,
        ]);
    }

    /**
     * Получить информацию о конкретном клиенте
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/spa-clients/{id}",
     *     tags={"SpaClients"},
     *     summary="Получить информацию о конкретном SPA клиенте",
     *     description="Возвращает информацию о конкретном клиенте SPA-приложения по его ID",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID клиента",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SpaClientResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Клиент не найден",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Клиент не найден")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $client = SpaClient::find($id);

        if (! $client) {
            return response()->json([
                'success' => false,
                'message' => 'Клиент не найден',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $client,
        ]);
    }

    /**
     * Обновить данные клиента
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *     path="/spa-clients/{id}",
     *     tags={"SpaClients"},
     *     summary="Обновить данные SPA клиента",
     *     description="Обновляет данные клиента SPA-приложения по его ID",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID клиента",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="name", type="string", example="Иван Петров"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="phone", type="string", example="+7 (999) 123-45-67"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Клиент успешно обновлен",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SpaClientResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Клиент не найден",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Клиент не найден")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SpaClientErrorResponse")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $client = SpaClient::find($id);

        if (! $client) {
            return response()->json([
                'success' => false,
                'message' => 'Клиент не найден',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:spa_clients,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $validator->errors(),
            ], 422);
        }

        $client->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Клиент успешно обновлен',
            'data' => $client,
        ]);
    }
}
