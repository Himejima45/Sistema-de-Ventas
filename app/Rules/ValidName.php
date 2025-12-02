<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // The regex pattern allows letters (any language), numbers, spaces, hyphens,
        // slashes, dots, parentheses, plus sign, degree symbol, ampersand, and quotes
        return preg_match('/^[\p{L}\p{N}\s\-\/\.\(\)\+°&"]+$/u', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El campo :attribute contiene caracteres inválidos. Solo se permiten letras, números, espacios, y los siguientes símbolos: - / . ( ) + ° & "';
    }
}