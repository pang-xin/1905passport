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

Route::post('/user/reg', 'Api\ApiController@reg');

Route::post('/user/login', 'Api\ApiController@login');
Route::get('/user/token', 'Api\ApiController@token');

Route::post('/user/auth', 'Api\ApiController@auth');
Route::get('/api/love', 'Api\ApiController@love');

Route::get('/test/sign', 'TestController@sign');//验签
Route::post('/test/sign2', 'TestController@sign2');//post验签
Route::get('/test/sign3', 'TestController@key_sign');//公钥验签


Route::get('/test/decrypt', 'TestController@decrypt');//公钥验签
