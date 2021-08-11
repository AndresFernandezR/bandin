<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;

use App\Models\{Company, User};
use App\Services\Auth\{AdminServiceClass, CompanyServiceClass, RegularUserServiceClass};

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
        $companyService = new CompanyServiceClass;

        $validatedData = $companyService->validateRequest(
                                                            $request->all(), 
                                                            $companyService->registerRules()
                                                        );
        
        if ($validatedData->fails()) {
            return response([
                'errors' => $validatedData->errors()->all()
            ], 400);
        }

        //Create new company
        $company = $companyService->createCompany($request->name, $this->newCompanyKey());

        // Add data to new company's user
        $user_data = [];
        $user_data['name'] = $request->name;
        $user_data['email'] = $request->email;
        $user_data['password'] = Hash::make($request->password);
        $user_data['user_type_id'] = 2;
        $user_data['company_id'] = $company->id;

        // Create new company user
        $user = User::create($user_data);

        return response([
                        'success'       => true,
                        'user'          => $user
                        ]);
    }

    /**
     * Register User
     * 
     * @param Request $request
     * @return Response id,Token
     */
    public function registerUser(Request $request)
    {
        $userService = new RegularUserServiceClass;

        $validatedData = $userService->validateRequest(
            $request->all(),
            $userService->registerRules()
        );

        if ($validatedData->fails()) {
            return response([
                'errors' => $validatedData->errors()->all()
            ], 400);
        }

        $user_data = [];

        $user_data['name'] = $request->name;
        $user_data['email'] = $request->email;
        $user_data['password'] = Hash::make($request->password);
        $user_data['user_type_id'] = 2;
        $user_data['company_key'] = $this->newCompanyKey();

        $user = User::create($user_data);

        // $accesToken = $user->createToken('authToken')->accessToken;

        return response([
            'success'       => true,
            'user'          => $user,
            // 'token_type'    => 'Bearer',
            // 'access_token'  => $accesToken,
            // 'expires_in'    => "1 hour"
        ]);
    }

    /**
     * Company Login Method
     * 
     * @param Request $request
     * @return Response $response
     */
    public function companyLogin(Request $request)
    {
        $companyService = new CompanyServiceClass;

        $validatedData = $companyService->validateRequest(
                                                            $request->all(),
                                                            $companyService->loginRules()
                                                        );

        if ($validatedData->fails()) {
            return response([
                'errors' => $validatedData->errors()->all()
            ], 400);
        }

        // Take only email and password
        $loginData = $companyService->getLoginData($request->all());

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
                        ], 200);
    }

    /**
     * Admin login method
     * 
     * @param Request $request
     * @return Response $response
     */
    public function adminLogin(Request $request)
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
     * Company Login Method
     * 
     * @param Request $request
     * @return Response $response
     */
    public function userLogin(Request $request)
    {
        $companyService = new CompanyServiceClass;

        $validatedData = $companyService->validateRequest(
            $request->all(),
            $companyService->loginRules()
        );

        if ($validatedData->fails()) {
            return response([
                'errors' => $validatedData->errors()->all()
            ], 400);
        }

        // Take only email and password
        $loginData = $companyService->getLoginData($request->all());

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
        ], 200);
    }

    /**
     * Set company's autoincremental key
     * 
     * @param none
     * @return string $key
     */
    public function newCompanyKey() : string
    {
        $last_id = Company::orderBy('id','desc')->first();
        
        $last_id = $last_id->company_key ?? 0;
        $last_id = (int) $last_id + 1;

        return str_pad($last_id, 4, "0", STR_PAD_LEFT);   
    }
}
