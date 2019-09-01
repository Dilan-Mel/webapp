<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/files/process', 'FileController@process')->name('process.file');
Route::delete('/files/delete', 'FileController@delete')->name('delete.file');

Route::post('/processForPII', 'HomeController@processForPII')->name('process.pii');