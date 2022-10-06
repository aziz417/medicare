<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// Application Commonly Used APIs
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/medicines.json', 'MedicineController@search')->name('medicines.search');
    Route::get('/discount', 'DiscountController@checkCouponCode')->name('discount.check');
    Route::post('/document/upload', 'DocumentController@upload')->name('document.upload');
});

// Application Third Party APIs Version 1
// These APIs will be use only for internal usage.
// So we can call these APIs as Private API
Route::prefix('v1')->namespace('V1')->group(function() {
    Route::get('/', 'AppController@index');
    Route::post('/auth/login', 'AuthController@login');
    Route::post('/auth/register', 'AuthController@register');
    
    Route::middleware('auth:api')->group(function(){
        Route::get('/me', 'AuthController@me');
    });

    Route::get('/users/doctors', 'UsersController@doctors');
});

// Application Third Party APIs Version 2
// Which will be use for public usage
// These API can use for external application
Route::prefix('v2')->namespace('V2')->group(function() {
    Route::post('auth/login', 'AuthController@login');
    Route::post('auth/register', 'RegisterController@register');
    
    Route::post('auth/verify/mobile', 'VerifyController@verifyMobile');
    Route::post('auth/resend/mobile', 'VerifyController@resendMobile');
    Route::post('auth/resend/email', 'VerifyController@resendEmail');

    Route::post('auth/logout', 'AuthController@logout');
    Route::post('auth/refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');

    Route::middleware('auth:jwt')->group(function() {
        Route::get('profile', 'ProfileController@index');
        Route::put('profile', 'ProfileController@update');

        Route::get('departments', 'DepartmentController@index');
        Route::get('departments/{department}', 'DepartmentController@withUser');

        Route::get('patients', 'PatientController@index');
        Route::get('patients/{patient}', 'PatientController@show');

        Route::get('doctors', 'DoctorController@index');
        Route::get('doctors/{doctor}', 'DoctorController@show');
        
        Route::get('doctors/{doctor}/schedules', 'DoctorScheduleController@index');
        Route::post('doctors/{doctor}/schedules', 'DoctorScheduleController@store');
        Route::delete('doctors/{doctor}/schedules/{schedule}', 'DoctorScheduleController@delete');

        Route::get('doctors/{doctor}/reviews', 'DoctorReviewController@index');
        Route::post('doctors/{doctor}/reviews', 'DoctorReviewController@store');

        Route::get('appointments', 'AppointmentController@index');
        Route::get('appointments/{appointment}', 'AppointmentController@show');
        Route::delete('appointments/{appointment}', 'AppointmentController@delete');

        Route::post('booking', 'BookingController@submit');
        Route::get('payment/check', 'PaymentController@status');

    });
});


// Application Third Party APIs Version 3
// Which will be use for public usage
// These API can use for external application
Route::prefix('v3')->namespace('V3')->group(function() {

    Route::get('doctor-of-department', 'DoctorController@doctor_of_department');
    Route::get('doctors', 'DoctorController@index');
    Route::get('doctors/{doctor}', 'DoctorController@show');
});

