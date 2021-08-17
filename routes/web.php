<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/experiment', 'ExperimentController@index')->name('experiment');

Auth::routes();

Route::get('/experiment', 'ExperimentController@index')->name('experiment');
Route::get('/experiment/createexperiment', 'ExperimentController@createexperiment')->name('createexperiment');
Route::get('/experimentedit', 'ExperimentController@experimentedit')->name('experimentedit');
Route::post('/setexperiement', 'ExperimentController@setexperiement')->name('setexperiement');
Route::post('/deleteexperiment', 'ExperimentController@deleteexperiment')->name('deleteexperiment');
Route::get('/downloadexperiment', 'ExperimentController@downloadexperiment')->name('downloadexperiment');


Auth::routes();

Route::get('/relationship', 'RelationshipController@index')->name('relationship');

Auth::routes();
Route::post('/addrelationshiptype', 'RelationshipController@addrelationshiptype')->name('addrelationshiptype');
Auth::routes();
Route::post('/editrelationshiptype', 'RelationshipController@editrelationshiptype')->name('editrelationshiptype');

Auth::routes();
Route::get('/user', 'UserController@index')->name('user');
Auth::routes();
Route::post('/edituser', 'UserController@edituser')->name('edituser');
Route::post('/adduser', 'UserController@adduser')->name('addtuser');
Route::post('/saveuserrelationship', 'UserController@saveuserrelationship')->name('saveuserrelationship');
Route::get('/user/deletecontact/id/{id}', 'UserController@deletecontact')->name('deletecontact');

Auth::routes();
Route::get('/admin', 'AdminuserController@index')->name('admin');
Route::post('/editadmin', 'AdminuserController@editadmin')->name('editadmin');
Route::post('/addadmin', 'AdminuserController@addadmin')->name('addadmin');
Route::post('/deleteadmin', 'AdminuserController@deleteadmin')->name('deleteadmin');


Auth::routes();
Route::get('/useredit', 'UsereditController@index')->name('useredit');
Auth::routes();
Route::post('/useredit', 'UsereditController@setdata')->name('useredit');
