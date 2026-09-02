<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* WhatsApp Webhook Receiver for Delivery & Read Statuses */
Route::get('/whatsapp/webhook', 'Api\WhatsAppWebhookController@verify')->name('api.whatsapp.webhook.verify');
Route::post('/whatsapp/webhook', 'Api\WhatsAppWebhookController@handle')->name('api.whatsapp.webhook.handle');

