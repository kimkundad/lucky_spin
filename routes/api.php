<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::post('logout', [AuthController::class, 'logout']);

// ใช้ยิง cronjob ทุกวันหลังเที่ยงคืน ลบคนที่เช็คอินรายวันได้ 7 วันแล้ว
//http://localhost:8000/api/delete_point_reward_all
Route::get('delete_point_reward_all', [ApiController::class, 'delete_point_reward_all']);

Route::middleware('auth:api')->group( function () {
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('get_point_reward', [ApiController::class, 'get_point_reward']);
    Route::get('getPoint', [ApiController::class, 'getPoint']);
    Route::get('spin_wheel', [ApiController::class, 'spin_wheel']);
    Route::get('data_wheel', [ApiController::class, 'data_wheel']);
    Route::get('data_labels', [ApiController::class, 'data_labels']);
    Route::get('addwheelresult', [ApiController::class, 'addwheelresult']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});