<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;

use App\Models\{User};
use App\Services\Auth\{AdminServiceClass};
use App\Http\Requests\{AdminLoginRequest};
use App\Rules\{UserTypeEmail};

class AuthController extends Controller
{
    /**
     * Constructor
     * 
     * @param
     * @return
     */
    public function __construct()
    {
        
    }

    /**
     * Get Token
     * 
     * @param Request $request
     * @return Response token
     */
    public function getToken(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
                                             'client_secret'      => 'required|exists:oauth_clients,secret',
                                             'email'              => 'required|email|exists:users,email',
                                             ]);

        if ($validatedData->fails()) {
            return response([
                'errors' => $validatedData->errors()->all()
            ], 400);
        }

        $user = User::whereEmail($request->email)
                        ->first();

        $accesToken = $user->createToken('authToken')->accessToken;

        return response([
                        'success'       => true,
                        'token_type'    => 'Bearer',
                        'access_token'  => $accesToken,
                        'expires_in'    => "1 hour"
                        ]);
    }
    
    /**
     * Register Company
     * 
     * @param Request $request
     * @return Response id,Token
     */
    public function registerCompany(Request $request)
    {
        $validatedData = $request->validate([
                                            'name'      => 'required|max:255',
                                            'email'     => 'required|email|unique:users',
                                            'password'  => 'required|confirmed',
                                            ]);

        $validatedData['password'] = Hash::make($request->password);
        $validatedData['user_type_id'] = 2;
        $validatedData['company_key'] = $this->getCompanyKey();
        info($validatedData);
        $user = User::create($validatedData);

        $accesToken = $user->createToken('authToken')->accessToken;

        return response([
                        'success'       => true,
                        'user'          => $user,
                        'token_type'    => 'Bearer',
                        'access_token'  => $accesToken,
                        'expires_in'    => "1 hour"
                        ]);
    }

    /**
     * Admin login method
     * 
     * @param Request $request
     * @return Response $response
     */
    public function loginAdmin(Request $request)
    {
        $adminService = new AdminServiceClass;

        $validatedData = $adminService->validateRequest($request->all());

        if ($validatedData->fails()) {
            return response([
                                'errors' => $validatedData->errors()->all()
                            ], 400);
        }

        // Take only email and password
        $loginData = $adminService->getLoginData($request->all());

        // Attempts to login user with specified credentials
        if (!auth()->attempt($loginData)) {
            return response([
                                'message' => 'Datos de acceso incorrectos'
                            ], 400);
        }

        // If login was succesful then create a Token
        $accesToken = auth()->user()->createToken('authToken')->accessToken;

        return response([
                        'success'       => true,
                        'user'          => auth()->user(),
                        'token_type'    => 'Bearer',
                        'access_token'  => $accesToken,
                        'expires_in'    => "1 hour"
                        ]);
    }

    /**
     * 
     */
    public function getResponseFailure()
    {
        $errors = \Session::get('errors');
        $errors = json_decode($errors);

        dd($errors);

        $result             = [];
        $result['success']  = false;
        
        foreach ($errors as $error) {
            $result['errors'][] = $error;
        }


        return response($result, 400);
    }

    public function getCompanyKey()
    {
        $last_id = User::whereUserTypeId('2')
                        ->orderBy('company_key','desc')
                        ->first();
        
        $last_id = $last_id->company_key ?? 0;
        $last_id = (int)$last_id + 1;

        $id = str_pad($last_id, 4, "0", STR_PAD_LEFT);
        
        return $id;
    }
}
