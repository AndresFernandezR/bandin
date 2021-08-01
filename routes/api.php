<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthController,HomeController};
use App\Http\Resources\HomeResource;
use Illuminate\Http\Resources\Json\JsonResource;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
*/
Route::post('/login-admin', 'API\AuthController@loginAdmin'); // Admin

Route::get('/errors', 'API\AuthController@getResponseFailure'); // Errores

Route::post('/register-company','API\AuthController@registerCompany');
Route::post('/get-token', 'API\AuthController@getToken');
Route::get('/home', 'API\HomeController@typeUser');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::get('/users', 'API\UserController@index');