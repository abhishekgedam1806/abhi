<?php

/* * ****** Header & Footer Menu Management Start ********** */
Route::get('manage-header', array_merge(['uses' => 'Admin\MenuManagementController@manageHeader'], $all_users))->name('admin.manage.header');
Route::get('manage-footer', array_merge(['uses' => 'Admin\MenuManagementController@manageFooter'], $all_users))->name('admin.manage.footer');

Route::post('menu-item/store', array_merge(['uses' => 'Admin\MenuManagementController@storeMenuItem'], $all_users))->name('admin.menu.store');
Route::post('menu-item/update/{id}', array_merge(['uses' => 'Admin\MenuManagementController@updateMenuItem'], $all_users))->name('admin.menu.update');
Route::delete('menu-item/delete/{id}', array_merge(['uses' => 'Admin\MenuManagementController@deleteMenuItem'], $all_users))->name('admin.menu.delete');
Route::post('menu-item/toggle-status/{id}', array_merge(['uses' => 'Admin\MenuManagementController@toggleStatus'], $all_users))->name('admin.menu.toggle');
Route::post('menu-item/reorder', array_merge(['uses' => 'Admin\MenuManagementController@reorderMenuItems'], $all_users))->name('admin.menu.reorder');

Route::post('header-settings/update', array_merge(['uses' => 'Admin\MenuManagementController@updateHeaderSettings'], $all_users))->name('admin.header.settings.update');
Route::post('footer-settings/update', array_merge(['uses' => 'Admin\MenuManagementController@updateFooterSettings'], $all_users))->name('admin.footer.settings.update');
/* * ****** Header & Footer Menu Management End ********** */
