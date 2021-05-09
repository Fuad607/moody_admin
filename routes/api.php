<?php

use Illuminate\Http\Request;

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


Route::get('userdatas','UserdatasController@index');
Route::get('userdatas/{id}','UserdatasController@show');
Route::post('userdatas','UserdatasController@store');
Route::put('userdatas','UserdatasController@store');
Route::delete('userdatas/{id}','UserdatasController@destroy');

Route::get('users','UsersController@index');
Route::get('users/email/{email}','UsersController@checkEmail');
Route::get('users/{id}','UsersController@show');
Route::post('users','UsersController@add');
Route::put('users','UsersController@setData');
Route::put('users/checkUser','UsersController@checkUser');
Route::delete('users/{id}','UsersController@destroy');


Route::get('userdetail','UserdetailController@index');
