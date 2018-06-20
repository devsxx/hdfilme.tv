<?php


Route::get('/coupons/download', 'Anhi\Coupon\Controllers\CouponController@downloadCodes');

Route::get('/coupons', 'Anhi\Coupon\Controllers\CodeController@checkCode');