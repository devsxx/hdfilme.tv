<?php

Route::group(['namespace' => 'Anhi\Userprofile\Controllers'], function () {

	Route::post('/user/edit', 'UserController@editInfo');

	Route::post('/user/avatar', 'UserController@updateAvatar');

	Route::delete('/user/avatar', 'UserController@deleteAvatar');

});


Route::group(['namespace' => 'Anhi\Userprofile\Controllers', 'prefix' => 'mobile'], function () {

	Route::post('/user/edit', 'UserController@editInfo');

	Route::post('/user/avatar', 'UserController@updateAvatar');

	Route::delete('/user/avatar', 'UserController@deleteAvatar');

});