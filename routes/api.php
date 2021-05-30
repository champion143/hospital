<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\UserController;
use App\Http\Middleware\ApiAuth;

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

Route::post('/hospital', [ApiController::class,'getHospital'])->name('hospital');
Route::post('/sendOtp', [UserController::class,'sendOtp'])->name('sendOtp');
Route::post('/firstNameAndEmailSave', [ApiController::class,'firstNameAndEmailSave'])->name('firstNameAndEmailSave');
Route::get('/getProfile', [ApiController::class,'getProfile'])->name('getProfile');
