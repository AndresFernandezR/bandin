<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\User;

class UserTypeEmail implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user_type)
    {
        $this->user_type = $user_type;
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
        $user = null;

        // Verify if user exists in specified user type
        $user = User::whereEmail($value)
                    ->whereUserTypeId($this->user_type)
                    ->first();

        return ($user) ? true 
                        : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Los datos de acceso no son correctos.';
    }
}
