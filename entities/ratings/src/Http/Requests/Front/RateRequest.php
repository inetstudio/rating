<?php

namespace InetStudio\RatingsPackage\Ratings\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\RatingsPackage\Ratings\Validation\Rules\IsRateable;
use InetStudio\RatingsPackage\Ratings\Contracts\Http\Requests\Front\RateRequestContract;

/**
 * Class RateRequest.
 */
class RateRequest extends FormRequest implements RateRequestContract
{
    /**
     * @var array
     */
    protected $rates = [
        'dislike' => 0.0,
        'like' => 5.0,
    ];

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
            'rating.in' => 'Некорректный тип оценки',
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
            'rating' => 'in:0.0,5.0',
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
        $rating = $this->rates[$this->route('rate', '')] ?? -1;

        $data = compact('type','id', 'item', 'rating');
        $this->merge($data);
        request()->merge($data);
    }
}
