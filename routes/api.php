<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Forgot\ForgotController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\UsersController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Search\FilterController;
use App\Http\Controllers\Api\Event\EventController;
use App\Http\Controllers\Api\Respecto\RespectoController;
use App\Http\Controllers\Api\Balance\BalanceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [RegisterController::class, 'register']);
Route::post('resend_code_for_register', [RegisterController::class, 'resend_code_for_register']);
Route::post('validation_register_code', [RegisterController::class, 'validation_register_code']);
Route::post('login', [RegisterController::class, 'login']);


Route::post('send_code_for_forgot_password', [ForgotController::class, 'send_code_for_forgot_password']);
Route::post('validation_forgot_password_code', [ForgotController::class, 'validation_forgot_password_code']);
Route::post('update_password_for_forgot_password', [ForgotController::class, 'update_password_for_forgot_password']);

Route::group(['middleware' => ['auth:api']], function () {

    Route::post('update_user_data', [ProfileController::class, 'update_user_data']);
    Route::post('user_add_new_email', [ProfileController::class, 'user_add_new_email']);
    Route::post('validation_new_email_code', [ProfileController::class, 'validation_new_email_code']);


    Route::post('get_fild_grafik', [FilterController::class  , 'get_fild_grafik']);


    Route::post('create_new_event', [EventController::class , 'create_new_event']);

    Route::post('get_events', [EventController::class, 'get_events']);

    Route::post('respect', [RespectoController::class, 'respect']);

    Route::get('single_page_user/{id}', [UsersController::class, 'single_page_user']);
    Route::get('get_balance_history', [BalanceController::class, 'get_balance_history']);
    Route::post('get_users_for_send_bonus', [BalanceController::class, 'get_users_for_send_bonus']);
    Route::post('send_bonus_for_other_user', [BalanceController::class, 'send_bonus_for_other_user']);
});
Route::get('get_sports_type', [CategoryController::class, 'get_sports_type']);
Route::get('get_city', [CategoryController::class, 'get_city']);


Route::post('get_filds', [FilterController::class, 'get_filds']);