<?php

/*
|--------------------------------------------------------------------------
| WhatsApp Notification System Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('whatsapp', array_merge(['uses' => 'Admin\WhatsAppController@index'], $all_users))->name('admin.whatsapp.index');
Route::get('whatsapp/settings', array_merge(['uses' => 'Admin\WhatsAppController@settings'], $all_users))->name('admin.whatsapp.settings');
Route::post('whatsapp/settings/update', array_merge(['uses' => 'Admin\WhatsAppController@updateSettings'], $all_users))->name('admin.whatsapp.settings.update');
Route::post('whatsapp/test-connection', array_merge(['uses' => 'Admin\WhatsAppController@testConnection'], $all_users))->name('admin.whatsapp.test_connection');
Route::get('whatsapp/templates', array_merge(['uses' => 'Admin\WhatsAppController@templates'], $all_users))->name('admin.whatsapp.templates');
Route::put('whatsapp/templates/{id}/update', array_merge(['uses' => 'Admin\WhatsAppController@updateTemplate'], $all_users))->name('admin.whatsapp.templates.update');
Route::get('whatsapp/logs', array_merge(['uses' => 'Admin\WhatsAppController@logs'], $all_users))->name('admin.whatsapp.logs');
Route::post('whatsapp/logs/{id}/resend', array_merge(['uses' => 'Admin\WhatsAppController@resendNotification'], $all_users))->name('admin.whatsapp.logs.resend');
