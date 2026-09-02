<?php

/* * ****** Admin Business Management Routes ********** */
Route::get('list-businesses', array_merge(['uses' => 'Admin\BusinessController@index'], $all_users))->name('admin.list.businesses');
Route::get('create-business', array_merge(['uses' => 'Admin\BusinessController@create'], $all_users))->name('admin.create.business');
Route::post('store-business-admin', array_merge(['uses' => 'Admin\BusinessController@store'], $all_users))->name('admin.store.business');
Route::get('edit-business-admin/{id}', array_merge(['uses' => 'Admin\BusinessController@edit'], $all_users))->name('admin.edit.business');
Route::put('update-business-admin/{id}', array_merge(['uses' => 'Admin\BusinessController@update'], $all_users))->name('admin.update.business');
Route::delete('delete-business', array_merge(['uses' => 'Admin\BusinessController@delete'], $all_users))->name('admin.delete.business');
Route::post('bulk-delete-businesses', array_merge(['uses' => 'Admin\BusinessController@bulkDelete'], $all_users))->name('admin.bulk.delete.businesses');
Route::post('toggle-business-verify', array_merge(['uses' => 'Admin\BusinessController@toggleVerification'], $all_users))->name('admin.toggle.business.verify');
Route::post('toggle-business-featured', array_merge(['uses' => 'Admin\BusinessController@toggleFeatured'], $all_users))->name('admin.toggle.business.featured');
Route::post('toggle-business-active', array_merge(['uses' => 'Admin\BusinessController@toggleActive'], $all_users))->name('admin.toggle.business.active');
Route::get('fetch-businesses', array_merge(['uses' => 'Admin\BusinessController@fetchData'], $all_users))->name('admin.fetch.businesses');

/* * ****** Admin Business Categories Routes ********** */
Route::get('list-business-categories', array_merge(['uses' => 'Admin\BusinessCategoryController@index'], $all_users))->name('admin.list.business_categories');
Route::get('create-business-category', array_merge(['uses' => 'Admin\BusinessCategoryController@create'], $all_users))->name('admin.create.business_category');
Route::post('store-business-category', array_merge(['uses' => 'Admin\BusinessCategoryController@store'], $all_users))->name('admin.store.business_category');
Route::get('edit-business-category/{id}', array_merge(['uses' => 'Admin\BusinessCategoryController@edit'], $all_users))->name('admin.edit.business_category');
Route::put('update-business-category/{id}', array_merge(['uses' => 'Admin\BusinessCategoryController@update'], $all_users))->name('admin.update.business_category');
Route::delete('delete-business-category', array_merge(['uses' => 'Admin\BusinessCategoryController@delete'], $all_users))->name('admin.delete.business_category');
Route::post('bulk-delete-business-categories', array_merge(['uses' => 'Admin\BusinessCategoryController@bulkDelete'], $all_users))->name('admin.bulk.delete.business_categories');
Route::get('fetch-business-categories', array_merge(['uses' => 'Admin\BusinessCategoryController@fetchData'], $all_users))->name('admin.fetch.business_categories');
