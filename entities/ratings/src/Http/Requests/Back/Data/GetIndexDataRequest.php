<?php

namespace InetStudio\RatingsPackage\Ratings\Http\Requests\Back\Data;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Back\Data\GetIndexDataRequestContract;

/**
 * Class GetIndexDataRequest.
 */
class GetIndexDataRequest extends FormRequest implements GetIndexDataRequestContract
{
    /**
     * Определить, авторизован ли пользователь для этого запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Сообщения об ошибках.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Правила проверки запроса.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
