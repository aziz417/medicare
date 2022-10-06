<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('appointments', 'AppointmentController')->except('edit');
Route::get('appointments/{appointment}/{action}', 'AppointmentController@showAction')->name('appointments.action');
Route::resource('doctors', 'DoctorController')->only('index', 'show');
Route::resource('sub-members', 'SubMemberController')->except('create', 'show');
Route::get('doctors/{doctor}/booking', 'BookingController@booking')->name('doctors.booking');
Route::post('doctors/{doctor}/booking', 'BookingController@bookingConfirm');
Route::post('doctors/{doctor}/review', 'DoctorController@saveReview')->name('doctor.review');
Route::resource('transactions', 'TransactionController')->only('index', 'show', 'destroy');
Route::resource('prescriptions', 'PrescriptionController')->only('index', 'show');
Route::resource('history', 'HealthHistoryController')->only('index', 'store', 'update', 'destroy');