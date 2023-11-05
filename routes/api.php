<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmpolyeeController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\MyAccountController;

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

Route::post('user/create-user', [UserController::class, 'create']);
Route::get('user/remove-user/{id}', [UserController::class, 'remove']);
Route::get('user/get/{id}', [UserController::class, 'get']);

Route::post('company/create-company', [CompanyController::class, 'create']);
Route::get('company/remove-company/{id}', [CompanyController::class, 'remove']);
Route::get('company/get/{id}', [CompanyController::class, 'get']);

Route::post('employee/create-employee', [EmpolyeeController::class, 'create']);
Route::get('employee/remove-employee/{id}', [EmpolyeeController::class, 'remove']);
Route::get('employee/get/{id}', [EmpolyeeController::class, 'get']);

Route::post('/my-account/password-change', [MyAccountController::class, 'change_password']); 
Route::post('/my-account/password-forget', [MyAccountController::class, 'forget_password']); 

Route::post('/my-account/reset-password', [MyAccountController::class, 'reset_forget_password']); 