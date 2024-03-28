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
Route::post('/body-conditions/store-or-update', [BodyConditionController::class, 'storeOrUpdate'])->name('body-conditions.store-or-update');
Route::get('/body-conditions', [BodyConditionController::class, 'index'])->name('body-conditions.index');
//Route::get('body-conditions/{id}/recommend-soups', 'BodyConditionController@recommendSoups')->name('body-conditions.recommend-soups');


Route::resource('admin/soups',App\Http\Controllers\Admin\SoupController::class)->names('admin.soups');
Route::resource('admin/conditions',App\Http\Controllers\Admin\ConditionController::class)->names('admin.update');
// 在 routes/web.php 中
Route::get('/soups', function () {
    $soups = \App\Models\Soup::all(); // 確保你有使用正確的命名空間
    return view('soups.index', compact('soups'));
})->middleware('auth'); // 保護這個路由，要求用戶必須登入
