<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    /**
     * Get Token
     * 
     * @param Request $request
     * @return Response token
     */
    public function getToken(Request $request)
    {
        $validatedData = $request->validate([
                                             'client_secret'      => 'required|exists:oauth_clients,secret',
                                             'email'              => 'required|email|exists:users,email',
                                             ]);

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

    public function login(Request $request)
    {
        $loginData =$request->validate([
                                        'email'     => 'required|email',
                                        'password'  => 'required'
                                        ]);
        if(!auth()->attempt($loginData)){
            return response(['message' => 'Invalid Credentials']);
        }

        $accesToken = auth()->user()->createToken('authToken')->accessToken;

        return response([
                        'success'       => true,
                        'user'          => auth()->user(),
                        'token_type'    => 'Bearer',
                        'access_token'  => $accesToken,
                        'expires_in'    => "1 hour"
                        ]);
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
