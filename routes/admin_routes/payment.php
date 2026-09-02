<?php

$all_users = ['allowed_roles' => ['SUP_ADM', 'SUB_ADM']];
$sup_only = ['allowed_roles' => 'SUP_ADM'];

Route::group(['prefix' => 'payment', 'namespace' => 'Admin'], function () use ($all_users, $sup_only) {
    Route::get('/', array_merge(['uses' => 'PaymentManagementController@index'], $all_users))->name('admin.payment.index');
    Route::get('/fetch-data', array_merge(['uses' => 'PaymentManagementController@fetchOrdersData'], $all_users))->name('admin.payment.fetchData');
    Route::get('/{id}', array_merge(['uses' => 'PaymentManagementController@show'], $all_users))->name('admin.payment.detail');
    Route::post('/{id}/refund', array_merge(['uses' => 'PaymentManagementController@processRefund'], $sup_only))->name('admin.payment.refund');
});
