<?php

/* * ******  SiteSetting Start ********** */
Route::get('edit-site-setting', array_merge(['uses' => 'Admin\SiteSettingController@editsiteSetting'], $all_users))->name('edit.site.setting');
Route::put('update-site-setting', array_merge(['uses' => 'Admin\SiteSettingController@updatesiteSetting'], $all_users))->name('update.site.setting');
Route::get('smtp-settings', array_merge(['uses' => 'Admin\SiteSettingController@smtpSettings'], $all_users))->name('admin.smtp.settings');
Route::post('smtp-settings', array_merge(['uses' => 'Admin\SiteSettingController@updateSmtpSettings'], $all_users))->name('admin.update.smtp.settings');
Route::post('test-smtp-email', array_merge(['uses' => 'Admin\SiteSettingController@testSmtpEmail'], $all_users))->name('test.smtp.email');
Route::get('otp-security-logs', array_merge(['uses' => 'Admin\SiteSettingController@otpSecurityLogs'], $all_users))->name('admin.otp.logs');
Route::delete('blocked-domain/{id}', array_merge(['uses' => 'Admin\SiteSettingController@deleteBlockedDomain'], $all_users))->name('admin.delete.blocked.domain');
Route::post('add-blocked-domain', array_merge(['uses' => 'Admin\SiteSettingController@addBlockedDomain'], $all_users))->name('admin.add.blocked.domain');
/* * ****** End SiteSetting ********** */
?>