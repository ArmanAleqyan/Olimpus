<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\SportTypeController;
use App\Http\Controllers\Admin\FildController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/NoAuth', function () {
    return response()->json([
        'status' => false,
        'message' => 'No Auth user'
    ],401);
})->name('NoAuth');

Route::get('/' , function (){
   return redirect()->route('login');
});

Route::prefix('admin')->group(function () {
    Route::middleware(['NoAuthUser'])->group(function () {
        Route::get('/login',[AdminLoginController::class,'login'])->name('login');
        Route::post('/logined',[AdminLoginController::class,'logined'])->name('logined');
    });

    Route::middleware(['AuthUser'])->group(function () {
        Route::get('HomePage', [AdminLoginController::class,'HomePage'])->name('HomePage');
        Route::get('logoutAdmin', [AdminLoginController::class,'logoutAdmin'])->name('logoutAdmin');
        Route::get('settingView', [AdminLoginController::class, 'settingView'])->name('settingView');
        Route::post('updatePassword', [AdminLoginController::class, 'updatePassword'])->name('updatePassword');

        Route::get('get_all_city' , [CityController::class, 'get_all_city'])->name('get_all_city');
        Route::get('create_city_page' , [CityController::class, 'create_city_page'])->name('create_city_page');
        Route::post('create_city' , [CityController::class, 'create_city'])->name('create_city');
        Route::post('update_city' , [CityController::class, 'update_city'])->name('update_city');
        Route::get('single_page_city/city_id={id}' , [CityController::class, 'single_page_city'])->name('single_page_city');
        Route::get('delete_city/city_id={id}' , [CityController::class, 'delete_city'])->name('delete_city');

        Route::get('all_sport_type', [SportTypeController::class, 'all_sport_type'])->name('all_sport_type');
        Route::get('create_sport_type_page', [SportTypeController::class, 'create_sport_type_page'])->name('create_sport_type_page');
        Route::post('create_sport', [SportTypeController::class, 'create_sport'])->name('create_sport');
        Route::post('update_sport', [SportTypeController::class, 'update_sport'])->name('update_sport');
        Route::get('single_page_sport/sport_id={id}', [SportTypeController::class, 'single_page_sport'])->name('single_page_sport');
        Route::get('delete_sport/sport_id={id}', [SportTypeController::class, 'delete_sport'])->name('delete_sport');


        Route::get('filds/fild_id={id}', [FildController::class, 'filds'])->name('filds');
        Route::get('create_fild_page', [FildController::class, 'create_fild_page'])->name('create_fild_page');
        Route::post('create_fild', [FildController::class, 'create_fild'])->name('create_fild');
        Route::post('update_fild', [FildController::class, 'update_fild'])->name('update_fild');
        Route::get('single_page_fild/fild_id={id}', [FildController::class, 'single_page_fild'])->name('single_page_fild');
        Route::get('delete_fild_grafik/grafik_id={id}', [FildController::class, 'delete_fild_grafik'])->name('delete_fild_grafik');
        Route::get('delete_photo/photo_id={id}', [FildController::class, 'delete_photo'])->name('delete_photo');
        Route::get('delete_fild/photo_id={id}', [FildController::class, 'delete_fild'])->name('delete_fild');
    });
});