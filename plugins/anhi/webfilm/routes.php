<?php


Route::group(['namespace' => 'Anhi\WebFilm\Controllers'], function () {

	Route::get('/sitemap.xml', 'WebFilmController@generateSitemapIndex');
	Route::get('/sitemap_static_pages.xml', function () {
		return \Response::view('anhi.webfilm::sitemap_static_page')->header('Content-Type', 'text/xml');
	});
	Route::get('/sitemap_{type}.xml', 'WebFilmController@generateSitemapChild');


	Route::post('login', 'AuthController@login')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');
	
	Route::get('logout', 'AuthController@logout');

	Route::get('social/redirect/{provider}', [

			'uses' => 'AuthController@getSocialRedirect',
			'as' => 'loginSocial'

		]);

	Route::get('social/handle/{provider}', ['uses' => 'AuthController@getSocialHandle']);

	Route::post('/movie/report', 'WebFilmController@report');

	Route::post('/movie/request', 'WebFilmController@request')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');

	Route::post('/movie/favorite/{id}', 'WebFilmController@favourite');

    Route::post('/movie/watch-later/{id}', 'WebFilmController@watchLater');

	Route::post('/movie/raty/{id}', 'WebFilmController@rate');

	Route::get('/movie/getlink/{movie_id}/{episode}', 'WebFilmController@getLink');

	Route::post('/movie-search', 'WebFilmController@search');

	Route::post('register', 'AuthController@register')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');

	Route::get('/notification/web', 'NotificationController@getWebNotifications');
	
	Route::get('/notification/web/new', 'NotificationController@countNewWebNotifications');

	Route::post('/contact', 'WebFilmController@sendContact')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');

	Route::post('/password/email', 'WebFilmController@sendResetPasswordMail')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');

	Route::post('/password/reset', 'WebFilmController@resetPassword');

	Route::post('/activation', 'AuthController@sendActivationLink')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');

	Route::get('/activation/confirm/{code}', 'AuthController@active');

});



Route::group(['namespace' => 'Anhi\WebFilm\Controllers', 'prefix' => 'mobile'], function () {


	Route::post('login', 'AuthController@login')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');
	
	Route::get('logout', 'AuthController@logout');

	Route::get('social/redirect/{provider}', [

			'uses' => 'AuthController@getSocialRedirect',
			'as' => 'loginSocial'

		]);

	Route::get('social/handle/{provider}', ['uses' => 'AuthController@getSocialHandle']);

	Route::post('/movie/report', 'WebFilmController@report');

	Route::post('/movie/request', 'WebFilmController@request')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');

	Route::post('/movie/favorite/{id}', 'WebFilmController@favourite');

    Route::post('/movie/watch-later/{id}', 'WebFilmController@watchLater');

	Route::post('/movie/raty/{id}', 'WebFilmController@rate');

	Route::get('/movie/getlink/{movie_id}/{episode}', 'WebFilmController@getLink');

	Route::post('/movie-search', 'WebFilmController@search');

	Route::post('register', 'AuthController@register')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');

	Route::get('/notification/web', 'NotificationController@getWebNotifications');
	
	Route::get('/notification/web/new', 'NotificationController@countNewWebNotifications');

	Route::post('/contact', 'WebFilmController@sendContact')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');

	Route::post('/password/email', 'WebFilmController@sendResetPasswordMail')->middleware('Alxy\Captcha\Middleware\CaptchaMiddleware');

	Route::post('/password/reset', 'WebFilmController@resetPassword');

	Route::post('/activation', 'AuthController@sendActivationLink');

	Route::get('/activation/confirm/{code}', 'AuthController@active');

});