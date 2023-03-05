<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register/checkEmail', [\App\Http\Controllers\RegisterController::class, 'registerEmail']); // check email and password
Route::post('register/sendMail', [\App\Http\Controllers\RegisterController::class, 'sendMail']); // insert user data to db
Route::get('profile/userProfile/{id}', [\App\Http\Controllers\ProfileController::class, 'userProfile'])->middleware('auth:sanctum'); // user Profile
Route::post('login/userLogin', [\App\Http\Controllers\LoginController::class, 'loginUser']);  // userLogin
Route::get('login/noLogin', [\App\Http\Controllers\LoginController::class, 'notLogin'])->name('login');  // userLogin