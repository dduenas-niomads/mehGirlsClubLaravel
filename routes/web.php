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

Route::get('/', function () {
    return "meh girls club";
});

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::post('/shopuser/update', 'App\Http\Controllers\HomeController@shopUserUpdate')->name('shopuser-update');
Route::get('/create-cupon', 'App\Http\Controllers\HomeController@createCupon')->name('create-cupon');
Route::post('/store-cupon', 'App\Http\Controllers\HomeController@storeCupon')->name('store-cupon');

