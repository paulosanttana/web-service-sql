<?php

Route::get('categorias', 'Api\CategoriaController@index');
Route::post('categorias', 'Api\CategoriaController@store');
Route::put('categorias/{id}', 'Api\CategoriaController@update');
Route::delete('categorias/{id}', 'Api\CategoriaController@delete');

Route::get('carros', 'Api\CarroController@index');
Route::post('carros', 'Api\CarroController@store');