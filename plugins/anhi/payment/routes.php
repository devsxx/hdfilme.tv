<?php


Route::group(['namespace' => 'Anhi\Payment\Controllers'], function () {

	Route::post('paypal', 'PaymentController@storePaypalTransctionInfo');

	Route::post('bitpay', 'PaymentController@createBitPayInvoice');

	Route::post('bitpay/handler', 'PaymentController@handleBitPaySuccessfulTransaction');

});


Route::group(['namespace' => 'Anhi\Payment\Controllers', 'prefix' => 'mobile'], function () {

	Route::post('paypal', 'PaymentController@storePaypalTransctionInfo');

	Route::post('bitpay', 'PaymentController@createBitPayInvoice');

	Route::post('bitpay/handler', 'PaymentController@handleBitPaySuccessfulTransaction');

});