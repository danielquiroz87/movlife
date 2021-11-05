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

Auth::routes();
Route::get('/', 'HomeController@index')->name('index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users', 'UsuariosController@index')->name('users');
Route::get('/users/new', 'UsuariosController@new')->name('user.new');
Route::post('/users/save', 'UsuariosController@save')->name('user.save');

Route::get('/users/register', 'UsuariosController@new')->name('signIn');


//Route::get('/login', 'HomeController@login')->name('login');

