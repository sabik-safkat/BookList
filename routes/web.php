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

Route::get('/', 'BookController@bookList')->name('home');
Route::post('/add-book', 'BookController@addBookList')->name('new-book-creation');
Route::get('/delete-book', 'BookController@deleteBook')->name('book-deletion');
Route::get('/export', 'BookController@export')->name('export');
Route::get('/export-xml', 'BookController@xml')->name('export-xml');