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
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', 'HomeController@index')->name('home.index');

    // Companies
    Route::group(['prefix' => 'companies', 'middleware' => 'role:super_admin|admin'], function () {
        Route::get('/',                                         ['as'=>'companies.index',   'uses'=>'CompanyController@index']);
        Route::get('/create',                                   ['as'=>'companies.create',  'uses'=>'CompanyController@create']);
        Route::post('/',                                        ['as'=>'companies.store',   'uses'=>'CompanyController@store']);
        Route::get('/{company_id}',                             ['as'=>'companies.show',    'uses'=>'CompanyController@show']);
        Route::match(['put', 'patch'], '/{company_id}',         ['as'=>'companies.update',  'uses'=>'CompanyController@update']);
        Route::delete('/{company_id}',                          ['as'=>'companies.destroy', 'uses'=>'CompanyController@destroy']);
        Route::get('/{company_id}/edit',                        ['as'=>'companies.edit',    'uses'=>'CompanyController@edit']);
        Route::get('/export/datatable',                         ['as'=>'companies.export',  'uses'=>'CompanyController@export']);
    });

    // Users
    Route::group(['prefix' => 'users', 'middleware' => 'role:super_admin|admin'], function () {
        Route::get('/',                                         ['as'=>'users.index',   'uses'=>'UserController@index']);
        Route::get('/create',                                   ['as'=>'users.create',  'uses'=>'UserController@create']);
        Route::post('/',                                        ['as'=>'users.store',   'uses'=>'UserController@store']);
        Route::get('/{user_id}',                                ['as'=>'users.show',    'uses'=>'UserController@show']);
        Route::match(['put', 'patch'], '/{user_id}',            ['as'=>'users.update',  'uses'=>'UserController@update']);
        Route::delete('/{user_id}',                             ['as'=>'users.destroy', 'uses'=>'UserController@destroy']);
        Route::get('/{user_id}/edit',                           ['as'=>'users.edit',    'uses'=>'UserController@edit']);
        Route::get('/export/datatable',                         ['as'=>'users.export',  'uses'=>'UserController@export']);
    });
    
});
