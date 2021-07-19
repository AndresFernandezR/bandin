<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserType;
use App\Http\Resources\HomeResource;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function typeUser()
    {   
        $userTypes = UserType::get([
                                    'type',
                                    'acronym'
                                ]);
        return response([
                        'success'       => true,
                        'user_types'    => $userTypes,
                        'message'       =>'Retrieved Succesfully'
                        ], 200);
    }

    public function index()
    {

    }
}
