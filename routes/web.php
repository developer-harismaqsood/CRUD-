<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmpolyeeController;
use App\Http\Controllers\MyAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/
Route::get('/', [DashboardController::class, 'landing_page']); 
Route::get('/dashboard', [DashboardController::class, 'index']); 

Route::get('/user/user-management', [UserController::class, 'index']); 
Route::get('/company/company-management', [CompanyController::class, 'index']); 

Route::get('/empolyee/empolyee-management', [EmpolyeeController::class, 'index']); 
Route::get('/my-account/password-setting', [MyAccountController::class, 'index']); 

Auth::routes();
