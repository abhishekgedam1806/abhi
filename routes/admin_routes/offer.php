<?php

$all_users = ['allowed_roles' => ['SUP_ADM', 'SUB_ADM']];

// Offers & Coupons Routes
Route::get('offers', array_merge(['uses' => 'Admin\OfferController@index'], $all_users))->name('admin.offers.index');
Route::get('offers/create', array_merge(['uses' => 'Admin\OfferController@create'], $all_users))->name('admin.offers.create');
Route::post('offers', array_merge(['uses' => 'Admin\OfferController@store'], $all_users))->name('admin.offers.store');
Route::get('offers/{id}/edit', array_merge(['uses' => 'Admin\OfferController@edit'], $all_users))->name('admin.offers.edit');
Route::put('offers/{id}', array_merge(['uses' => 'Admin\OfferController@update'], $all_users))->name('admin.offers.update');
Route::post('offers/{id}/toggle-status', array_merge(['uses' => 'Admin\OfferController@toggleStatus'], $all_users))->name('admin.offers.toggle-status');
Route::post('offers/{id}/duplicate', array_merge(['uses' => 'Admin\OfferController@duplicate'], $all_users))->name('admin.offers.duplicate');
Route::delete('offers/{id}', array_merge(['uses' => 'Admin\OfferController@destroy'], $all_users))->name('admin.offers.destroy');
Route::post('offers/generate-code', array_merge(['uses' => 'Admin\OfferController@generateCode'], $all_users))->name('admin.offers.generate-code');

// Coupon Redemptions Audit Report
Route::get('coupon-redemptions', array_merge(['uses' => 'Admin\CouponRedemptionController@index'], $all_users))->name('admin.coupon-redemptions.index');
