<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64Image implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = '/^data:image\/(png|jpg|jpeg|gif);base64,[A-Za-z0-9+\/]+={0,2}$/';

        if (!preg_match($regex, $value)) {
            $fail('The :attribute is not a valid base64 encoded image.');
        }
    }
}
