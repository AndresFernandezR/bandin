<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;


use App\Rules\{UserTypeEmail};

class AdminLoginRequest extends FormRequest
{
    /** 
     * The controller action to redirect to if validation fails.
     */
    // protected $redirectAction = 'API\AuthController@getResponseFailure';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function response(array $errors)
    {
        return new JsonResponse(['error' => $errors], 400);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                            'required',
                            new UserTypeEmail(1)
                        ],
            'password'      => 'required',
            'client_secret'  => 'required|exists:oauth_clients,secret'
        ];
    }
}
