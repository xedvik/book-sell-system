<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_url' => 'required|string|url|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Название книги обязательно',
            'title.max' => 'Название книги не должно превышать 255 символов',
            'description.required' => 'Описание книги обязательно',
            'cover_url.required' => 'URL обложки обязателен',
            'cover_url.url' => 'Введите корректный URL обложки',
            'price.required' => 'Цена книги обязательна',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            'quantity.required' => 'Количество книг обязательно',
            'quantity.integer' => 'Количество должно быть целым числом',
            'quantity.min' => 'Количество не может быть отрицательным',
            'authors.required' => 'Необходимо выбрать хотя бы одного автора',
            'authors.min' => 'Необходимо выбрать хотя бы одного автора',
            'authors.*.exists' => 'Выбранный автор не существует',
        ];
    }
}
