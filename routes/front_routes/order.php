<?php

/* * ******** Modern Unified Payment & Razorpay Gateway Routes ************ */
Route::get('checkout/package/{id}', 'PaymentController@checkout')->name('payment.checkout');
Route::post('payment/apply-coupon', 'PaymentController@applyCoupon')->name('payment.apply-coupon');
Route::post('payment/remove-coupon', 'PaymentController@removeCoupon')->name('payment.remove-coupon');
Route::post('payment/verify', 'PaymentController@verifyPayment')->name('payment.verify');
Route::post('payment/webhook/{gateway?}', 'PaymentController@handleWebhook')->name('payment.webhook');
Route::get('payment/success/{order_number}', 'PaymentController@paymentSuccess')->name('payment.success');
Route::get('payment/failed/{order_number}', 'PaymentController@paymentFailed')->name('payment.failed');
Route::get('payment/invoice/{order_number}', 'PaymentController@downloadInvoice')->name('payment.invoice');
Route::get('company-transactions', 'PaymentController@myPayments')->name('company.my.payments');

/* * ******** Legacy / Compatibility Order Routes ************ */
Route::get('order-free-package/{id}', 'PaymentController@checkout')->name('order.free.package');
Route::get('order-package/{id}', 'PaymentController@checkout')->name('order.package');
Route::get('order-upgrade-package/{id}', 'PaymentController@checkout')->name('order.upgrade.package');
Route::get('paypal-payment-status/{id}', 'OrderController@getPaymentStatus')->name('payment.status');
Route::get('paypal-upgrade-payment-status/{id}', 'OrderController@getUpgradePaymentStatus')->name('upgrade.payment.status');
Route::get('stripe-order-form/{id}/{new_or_upgrade}', 'StripeOrderController@stripeOrderForm')->name('stripe.order.form');
Route::post('stripe-order-package', 'StripeOrderController@stripeOrderPackage')->name('stripe.order.package');
Route::post('stripe-order-upgrade-package', 'StripeOrderController@stripeOrderUpgradePackage')->name('stripe.order.upgrade.package');

Route::get('payu-order-package', 'PaymentController@checkout')->name('payu.order.package');
Route::get('payu-order-package-status/', 'PayuController@orderPackageStatus')->name('payu.order.package.status');


