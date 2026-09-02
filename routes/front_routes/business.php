<?php

/* * ****** Business Directory Public Routes ********** */
Route::get('businesses', 'BusinessController@listBusinesses')->name('business.list');
Route::get('businesses/category/{category_slug}', 'BusinessController@listBusinessesByCategory')->name('business.list.category_only');
Route::get('businesses/location/{city_slug}', 'BusinessController@listBusinessesByCity')->name('business.list.city_only');
Route::get('businesses/{city_slug}', 'BusinessController@listBusinessesByCity')->name('business.list.city');
Route::get('businesses/{city_slug}/{category_slug}', 'BusinessController@listBusinesses')->name('business.list.category');
Route::get('business/{slug}', 'BusinessController@detail')->name('business.detail');
Route::post('business/lead/{id}', 'BusinessController@captureLead')->name('business.lead');
Route::post('business/claim/{id}', 'BusinessController@submitClaim')->name('business.claim');

/* * ****** Dedicated Business Login & Register Direct URLs ********** */
Route::get('business-login', function() {
    if (Auth::check() && Auth::user()->isJobSeeker()) {
        Auth::logout();
    }
    return redirect()->route('login', ['tab' => 'business']);
})->name('business.login');

Route::get('business-register', function() {
    if (Auth::check() && Auth::user()->isJobSeeker()) {
        Auth::logout();
    }
    return redirect()->route('register', ['tab' => 'business']);
})->name('business.register');

/* * ****** Dedicated Business Owner Dashboard Routes (100% Separate) ********** */
Route::get('business-dashboard', 'BusinessOwnerController@dashboard')->name('business.dashboard');
Route::get('my-businesses', 'BusinessOwnerController@myBusinesses')->name('my.businesses');
Route::get('add-business', 'BusinessOwnerController@create')->name('add.business');
Route::post('store-business', 'BusinessOwnerController@store')->name('store.business');
Route::get('edit-business/{id}', 'BusinessOwnerController@edit')->name('edit.business');
Route::put('update-business/{id}', 'BusinessOwnerController@update')->name('update.business');
Route::get('business-leads/{id}', 'BusinessOwnerController@leads')->name('business.leads');
Route::get('my-business-leads', 'BusinessOwnerController@allLeads')->name('business.all.leads');
Route::get('business-profile', 'BusinessOwnerController@profile')->name('business.owner.profile');
Route::post('business-profile', 'BusinessOwnerController@updateProfile')->name('business.owner.profile.update');

/* * ****** Business Packages & Paid Subscriptions (Payment Gateways) ********** */
Route::get('business-packages', 'BusinessPackageController@packages')->name('business.packages');
Route::get('business-order-free-package/{id}', 'BusinessPackageController@orderFreePackage')->name('business.order.free.package');
Route::get('business-order-test-package/{id}', 'BusinessPackageController@orderTestPackage')->name('business.order.test.package');
Route::get('business-pay-stripe/{id}', 'BusinessPackageController@stripeForm')->name('business.pay.stripe');
Route::post('business-stripe-order-package', 'BusinessPackageController@stripeOrderPackage')->name('business.stripe.order.package');
Route::get('business-pay-paypal/{id}', 'BusinessPackageController@paypalOrderPackage')->name('business.pay.paypal');
Route::get('business-paypal-status/{id}', 'BusinessPackageController@getPaypalStatus')->name('business.paypal.status');
Route::post('business-pay-payu', 'BusinessPackageController@payuOrderPackage')->name('business.pay.payu');
Route::post('business-payu-status', 'BusinessPackageController@payuOrderPackageStatus')->name('business.payu.status');
