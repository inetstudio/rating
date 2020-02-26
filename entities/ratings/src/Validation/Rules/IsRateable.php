<?php

namespace InetStudio\RatingsPackage\Ratings\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class IsRateable.
 */
class IsRateable implements Rule
{
    /**
     * @var string
     */
    protected $message;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     *
     * @throws BindingResolutionException
     */
    public function passes($attribute, $value)
    {
        [$type, $id] = explode('|', $value);
        $availableTypes = config('ratings_package_ratings.rateable', []);

        if (! isset($availableTypes[$type])) {
            $this->message = trans('ratings_package_ratings::errors.materialIncorrectType');

            return false;
        }

        $model = app()->make($availableTypes[$type]);

        if (! (! is_null($id) && $id > 0 && $item = $model::find($id))) {
            $this->message = trans('ratings_package_ratings::errors.materialNotFound');

            return false;
        }

        request()->merge(compact('item'));

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
