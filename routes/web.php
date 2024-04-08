<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\BodyConditionController;
//use App\Http\Controllers\Soup\SoupRecommendationController;

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

Route::get('/welcome', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/soups', [HomeController::class, 'soups'])->name('soups');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');

// 添加登录页面的路由
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// 添加处理登录请求的路由
Route::post('/login', [LoginController::class, 'login']);

// 添加注销的路由
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('/weather/{city}', [WeatherController::class, 'getWeather']);
Route::get('/soup/{id}', [HomeController::class, 'show'])->name('soup.show');
Route::get('/body-conditions', function () {
    return view('bodycondition'); // 路径对应你的视图文件
})->name('body-conditions');

Route::get('/body-conditions/filter', [BodyConditionController::class, 'filter']);
Route::get('/bodycondition/index', [BodyConditionController::class, 'index'])->name('bodycondition.index');
//Route::post('/body-conditions', [BodyConditionController::class, 'store'])->name('body.conditions.store');
Route::post('/body-conditions', [BodyConditionController::class, 'store'])->name('body-conditions.store');
Route::post('/body-conditions/update', [BodyConditionController::class, 'update'])->name('body-conditions.update');
Route::get('/body-conditions', [BodyConditionController::class, 'index'])->name('body-conditions.index');
//Route::get('body-conditions/{id}/recommend-soups', 'BodyConditionController@recommendSoups')->name('body-conditions.recommend-soups');



//Route::resource('admin/conditions',App\Http\Controllers\Admin\ConditionController::class)->names('admin.update');
//Route::prefix('admin')->group(function () {
    // 管理员登录页面
    //Route::get('/login', 'AdminController@showLoginForm')->name('Admin.login');

    // 处理管理员登录请求
    //Route::post('/login', 'AdminController@login')->name('admin.login.submit');

    // 管理员注销
    //Route::post('/logout', 'AdminController@logout')->name('Admin.logout');
//});
Route::prefix('admin')->group(function () {
    // ... 其他管理员路由 ...
    Route::get('/soups/{soup}', [App\Http\Controllers\Admin\SoupController::class, 'show'])->name('admin.soups.show');
    Route::get('/soups', [App\Http\Controllers\Admin\SoupController::class, 'index'])->name('admin.index');
});
Route::get('/body-conditions/create', [BodyConditionController::class, 'create'])->name('body-conditions.create');
Route::post('/body-conditions', [BodyConditionController::class, 'store'])->name('body-conditions.store');
Route::post('/body-conditions/update/{id}', [BodyConditionController::class, 'update']);
