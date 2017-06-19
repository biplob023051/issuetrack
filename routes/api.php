<?php

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');

Route::post('products/{product}/comment', 'ProductController@postComment');
Route::resource('products', 'ProductController');
