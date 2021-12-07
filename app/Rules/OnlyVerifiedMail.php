<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class OnlyVerifiedMail.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Rules
 */
class OnlyVerifiedMail implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strpos($value, '@gmail.com') !== false) {
            return true;
        }
        if (strpos($value, '@yahoo.com') !== false) {
            return true;
        }
        if (strpos($value, '@yahoo.co.id') !== false) {
            return true;
        }
        if (strpos($value, '@hotmail.com') !== false) {
            return true;
        }
        if (strpos($value, '@outlook.com') !== false) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Only gmail,yahoo,hotmail,outlook is allowed.';
    }
}