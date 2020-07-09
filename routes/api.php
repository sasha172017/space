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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/authenticate', 'Api\UserController@authenticate');
Route::post('/product', 'Api\ProductController@store')->middleware(\App\Http\Middleware\CheckToken::class);
Route::get('/user', 'Api\UserController@getUser')->middleware(\App\Http\Middleware\CheckToken::class);
Route::get('/logout', 'Api\UserController@logout')->middleware(\App\Http\Middleware\CheckToken::class);
Route::get('/products', 'Api\ProductController@index');
Route::get('/category', 'Api\CategoryController@index');
Route::get('/shop', 'Api\ShopController@index');
Route::get('/category/{id}', 'Api\CategoryController@show')->name('categoryShow');
Route::get('/product/{id}', 'Api\ProductController@show');
Route::get('/products/category/{id}', 'Api\ProductController@productsByCategory');
