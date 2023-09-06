<?php

use Illuminate\Support\Facades\Route;

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

//Auth::routes();

Route::get('/',                                 'HomeController@index');
Route::post('/',                                'HomeController@index');
Route::get('/orgid',                            'HomeController@orgid');
Route::post('/orgid',                           'HomeController@orgid');
Route::get('/orgidResult',                      'HomeController@orgidResult');
Route::get('logout',                            'HomeController@logout');

Route::get('/fiat/{targetuser}',                'FiatController@auth');
Route::get('/fiat/orgIdStartAuth/{targetuser}', 'FiatController@orgIdStartAuth');
Route::get('/fiat/orgIdAuthResult/{authRef}',   'FiatController@orgIdAuthResult');
Route::get('/fiat/eIdStartAuth/{targetuser}',   'FiatController@eIdStartAuth');
Route::get('/fiat/eIdAuthResult/{authRef}',     'FiatController@eIdAuthResult');

Route::get('/statistics',                       'StatisticsController@index');
Route::get('/statistics/{organization}',        'StatisticsController@listusers');
