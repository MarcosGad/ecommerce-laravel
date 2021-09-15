<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('collection','CollectTut@index');
Route::get('maincats','CollectTut@complex');
Route::get('main-cats','CollectTut@complexFilter');
Route::get('main-catss','CollectTut@complexTransform');
