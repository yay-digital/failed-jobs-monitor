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

Route::name('failed-jobs-monitor.')->group(function () {
    Route::get('/', 'IndexController@show')->name('index');

    Route::get('/job/{id}', 'JobController@show')->name('show');
    Route::get('/job/{id}/retry', 'JobController@retry')->name('retry');
    Route::get('/job/{id}/delete', 'JobController@confirmDelete')->name('confirmDelete');
    Route::post('/job/{id}/delete', 'JobController@delete')->name('delete');
});
