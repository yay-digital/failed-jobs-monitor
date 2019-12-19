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

use Illuminate\Support\Facades\Route;

Route::get('/', 'IndexController@show')->name('index');

Route::get('/jobs/{id}', 'JobController@show')->name('show');
Route::get('/jobs/{id}/retry', 'JobController@retry')->name('retry');
Route::get('/jobs/{id}/delete', 'JobController@confirmDelete')->name('confirmDelete');
Route::post('/jobs/{id}/delete', 'JobController@delete')->name('delete');
