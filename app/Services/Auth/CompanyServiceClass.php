<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Validator;

use App\Models\{Company};
use App\Rules\{UserTypeEmail};

class CompanyServiceClass 
{
    public function __construct() {

    }

    /**
     * Create company
     * 
     * @param
     * @return
     */
    public function createCompany($name, $company_key)
    {
        $company = new Company();
        $company->name = $name;
        $company->company_key = $company_key;
        $company->save();

        return $company;
    }

    /**
     * Validate login based on user type
     * 
     * @param $request
     * @return bool $valid
     */
    public function validateRequest(array $request, array $rules)
    {
        return Validator::make($request, $rules);
    }

    /**
     * Company Login Validation Rules
     * 
     * @param none
     * @return array
     */
    public function loginRules()
    {
        return [
                    'email' => ['required', new UserTypeEmail(2)],
                    'password'      => 'required',
                    'client_secret'  => 'required|exists:oauth_clients,secret'
                ];
    }

    /**
     * Company Register validation rules
     * 
     * @param none
     * @return array
     */
    public function registerRules()
    {
        return [
                    'name'      => 'required|max:255',
                    'email'     => 'required|email|unique:users',
                    'password'  => 'required|confirmed',
                ];
    }

    /**
     * Retrieve login data
     * 
     * @param array $request
     * @return array $loginData
     */
    public function getLoginData(array $request): array
    {
        return [
            'email'         => $request['email'],
            'password'      => $request['password'],
            'user_type_id'  => 2
        ];
    }
}