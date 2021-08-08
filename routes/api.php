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

//userrelationship
Route::get('userrelationship/getallbyid/{id}','UserrelationshipController@getAllById');
Route::get('userdatas/{id}','UserdatasController@show');
Route::post('userdatas','UserdatasController@store');
Route::put('userdatas','UserdatasController@store');
Route::delete('userdatas/{id}','UserdatasController@destroy');
//users
Route::get('users','UsersController@index');
Route::get('users/email/{email}','UsersController@checkEmail');
Route::get('users/{id}','UsersController@show');
Route::post('users','UsersController@add');
Route::put('users','UsersController@setData');
Route::put('users/checkUser','UsersController@checkUser');
Route::delete('users/{id}','UsersController@destroy');

//Survey
//Route::get('survey/{user_id}/start/{start}/end/{end}','SurveyController@index');
Route::get('survey/{user_id}','SurveyController@getAll');
Route::get('survey/{user_id}/{start}/{end}/{contacted_user_ids}','SurveyController@getAllById');
Route::get('survey/{id}','SurveyController@show');
Route::post('survey','SurveyController@store');
Route::put('survey','SurveyController@store');

//UserMeeting
Route::get('usermeeting/{survey_id}','UsermeetingController@index');
Route::get('usermeeting/{id}','UsermeetingController@show');
Route::post('usermeeting','UsermeetingController@store');
Route::put('usermeeting','UsermeetingController@store');

//Userspecialsituation usermeeting survey
Route::get('userspecialsituation/{survey_id}','UserspecialsituationController@index');
Route::get('userspecialsituation/{id}','UserspecialsituationController@show');
Route::post('userspecialsituation','UserspecialsituationController@store');
Route::put('userspecialsituation','UserspecialsituationController@store');
