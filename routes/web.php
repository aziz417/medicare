<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');
Route::get('/navigation.json', 'HomeController@routes')->name('routes');

Auth::routes(['verify' => true]);
// Mobile verification
Route::prefix('mobile')->middleware(['auth'])
->namespace('Auth')->name('verification.mobile.')->group(function() {
    Route::get('verify', 'MobileVerificationController@showNotice')->name('notice');
    Route::post('verify', 'MobileVerificationController@verify')->name('verify');
    Route::post('resend', 'MobileVerificationController@resend')->name('resend');
});
Route::get('auth/verify', 'Auth\VerificationController@showVerifyPage')->name('auth.verify');

// Migrate user routes
Route::prefix('auth/migrate')->namespace('Auth')->name('auth.migrate.')->group(function() {
    Route::get('/register', 'UserMigrationController@register')->name('register');
    Route::post('/register', 'UserMigrationController@submit');
});

Route::prefix('common')->middleware(['auth'])
->namespace('Common')->name('common.')->group(function() {
    // Profile Routes
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/profile/wallet', 'ProfileController@wallet')->name('profile.wallet');
    Route::post('/profile/wallet', 'ProfileController@withdraw');
    Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile', 'ProfileController@update')->name('profile.update');
    Route::post('/profile/password', 'ProfileController@passwordUpdate')->name('profile.password');
    // Ajax Routes
    Route::get('/message/{room}', 'ChatController@index')->name('message');
    Route::post('/message/{room}', 'ChatController@store');
    Route::post('/message/{room}/upload', 'ChatController@upload');
    Route::post('/notifications', 'UserActionController@markAllAsRead')->name('notifications');
    // Video Call
    Route::get('/video/{appointment}/call', 'VideoController@call')->name('video.call');
    // Prescription
    Route::get('download/prescription-{prescription}.pdf', 'PrescriptionController@download')->name('prescriptions.download');
});

Route::prefix('payment')->middleware(['auth'])
->namespace('Payment')->name('payment.')->group(function() {
    // check payment
    Route::get('/check/{driver}', 'PaymentController@checkPayment')->name('check');
    // Make Payment
    Route::get('/appointment/{appointment}', 'PaymentController@appointment')->name('appointment');
    // PayPal
    Route::get('/ppl/appointment/{appointment}/{transaction}/process', 'PaypalController@process')->name('paypal.process');
    Route::get('/ppl/appointment/{appointment}/{transaction}/cancel', 'PaypalController@cancel')->name('paypal.cancel');
    Route::get('/ppl/appointment/{appointment}/{transaction}/success', 'PaypalController@success')->name('paypal.success');
    // verify
    Route::get('/verify/appointment/{appointment}', 'PaymentController@verifyAppointmentPayment')->name('appointment.verify');
    // Manual Payment
    Route::get('/manual/{appointment}/{transaction}/verify', 'ManualPaymentController@show')->name('manual');
    Route::post('/manual/{appointment}/{transaction}/verify', 'ManualPaymentController@process')->name('manual.verify');
    // PortWallet
    Route::get('/prtw/appointment/{appointment}/{transaction}/process', 'PortWalletController@process')->name('portwallet.process');
    Route::get('/prtw/appointment/{appointment}/{transaction}/callback', 'PortWalletController@callback')->name('portwallet.callback');

    // AamarPay
    Route::any('/aamrpay/appointment/{appointment}/{transaction}/process', 'AamarPayController@process')->name('aamarpay.process');
    Route::any('/no-csrf/aamrpay/appointment/{appointment}/{transaction}/success', 'AamarPayController@success')->name('aamarpay.success');
    Route::any('/no-csrf/aamrpay/appointment/{appointment}/{transaction}/cancel', 'AamarPayController@cancel')->name('aamarpay.cancel');
});
Route::prefix('payment/ipn')->namespace('Payment')->name('payment.ipn.')->group(function() {
    // Instant Payment Notification (IPN) free from CSRF Token
    Route::any('/appointment/{appointment}/{transaction}', 'PortWalletController@ipnCallback')->name('portwallet');
});

Route::view('/app/support', 'common.support')->name('app.support');
Route::view('/app/close', 'common.video.close')->name('app.close');

Route::any('ping', function() {
    echo "pong";
});

Route::any('test', function() {
    return ['status' => 'success'];
});
