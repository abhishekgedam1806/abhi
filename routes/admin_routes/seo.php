<?php

/* * ******  SEO Field Start ********** */
Route::get('list-seo', array_merge(['uses' => 'Admin\SeoController@indexSeo'], $all_users))->name('list.seo');
Route::get('edit-seo/{id}/{industry_id?}', array_merge(['uses' => 'Admin\SeoController@editSeo'], $all_users))->name('edit.seo');
Route::put('update-seo/{id}', array_merge(['uses' => 'Admin\SeoController@updateSeo'], $all_users))->name('update.seo');
Route::delete('delete-seo', array_merge(['uses' => 'Admin\SeoController@deleteSeo'], $all_users))->name('delete.seo');
Route::post('bulk-delete-seos', array_merge(['uses' => 'Admin\SeoController@bulkDeleteSeos'], $all_users))->name('bulk.delete.seos');
Route::get('fetch-seo', array_merge(['uses' => 'Admin\SeoController@fetchSeoData'], $all_users))->name('fetch.data.seo');
/* * ****** End SEO Field ********** */
?>