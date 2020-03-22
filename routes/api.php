<?php

Route::get('categorias', 'Api\CategoriaController@index');
Route::post('categorias', 'Api\CategoriaController@store');