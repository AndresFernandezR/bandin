<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Validator;

use App\Services\Auth\Interfaces\{Auth};
use App\Rules\{UserTypeEmail};

class AdminServiceClass
{
    public function __construct() {

    }

    /**
     * Validate login based on user type
     * 
     * @param $request
     * @return bool $valid
     */
    public function validateRequest(array $request)
    {
        return Validator::make($request, $this->rules());
    }

    /**
     * Validation rules
     * 
     * @param none
     * @return array
     */
    public function rules()
    {
        return [
                    'email' => ['required', new UserTypeEmail(1)],
                    'password'      => 'required',
                    'client_secret'  => 'required|exists:oauth_clients,secret'
                ];
    }

    /**
     * Retrieve login data
     * 
     * @param array $request
     * @return array $loginData
     */
    public function getLoginData(array $request) : array
    {
        return [
                    'email' => $request['email'],
                    'password' => $request['password'],
                    'user_type_id' => 1
                ];
    }
}