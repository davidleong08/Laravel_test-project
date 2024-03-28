<?php
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// 用于返回 JSON 数据的路由
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 示例：返回所有汤品的路由
Route::middleware('guest')->get('/soups', [HomeController::class, 'soups']);
