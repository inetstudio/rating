<?php

namespace InetStudio\RatingsPackage\Ratings\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\RatingsPackage\Ratings\Validation\Rules\IsRateable;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Front\UnRateRequestContract;

/**
 * Class UnRateRequest.
 */
class UnRateRequest extends FormRequest implements UnRateRequestContract
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
        return [
            'type.required' => 'Тип материала обязателен для передачи.',
            'type.string' => 'Тип материала должен быть строкой.',
            'id.required' => 'Id материала обязателен для передачи.',
            'id.integer' => 'Id материала должен быть целочисленным значением.',
        ];
    }

    /**
     * Правила проверки запроса.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'id' => 'required|integer',
            'item' => new IsRateable(),
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $type = mb_strtolower($this->route('type', ''));
        $id = (int) $this->route('id', 0);
        $item = $type.'|'.$id;

        $data = compact('type','id', 'item');
        $this->merge($data);
        request()->merge($data);
    }
}
