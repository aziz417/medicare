<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Dashboard
Route::get('/home', 'HomeController@index')->name('home');

// Settings Page
Route::get('/settings/{type?}', 'SettingsController@index')->name('settings');
Route::post('/settings', 'SettingsController@update');
Route::get('/accounts/balance', 'HomeController@accounts')->name('accounts');

Route::get('/video/settings', 'VideoSettingController@index')->name('video.settings');
Route::post('/video/settings', 'VideoSettingController@update');
Route::post('/video/settings/zoom', 'VideoSettingController@updateZoomSetting')->name('video.zoom.settings');
Route::get('/video/zoom', 'VideoSettingController@connect')->name('video.zoom.authorize');
Route::get('/video/zoom/callback', 'VideoSettingController@zoomCallback')->name('video.zoom.callback');

Route::resource('departments', 'DepartmentController')->except('create', 'edit');
Route::resource('doctors', 'DoctorController')->except('create');
Route::resource('patients', 'PatientController')->except('create');
Route::resource('schedules', 'ScheduleController')->except('create', 'edit', 'show');
Route::resource('users', 'UserController')->except('create', 'show');
Route::resource('medicines', 'MedicineController')->except('create', 'show', 'edit');
Route::resource('appointments', 'AppointmentController')->except('create', 'edit');
Route::resource('history', 'UserHistoryController')->only('index', 'store', 'destroy', 'edit');
Route::get('history/edit', 'UserHistoryController@edit')->name('history.edit');
Route::put('history/item/update', 'UserHistoryController@update')->name('history.update');
Route::resource('advices', 'AdviceController')->only('index', 'store', 'update', 'destroy', 'edit');
Route::resource('icds', 'IcdController')->only('index', 'store', 'update', 'destroy', 'edit');
Route::resource('investigations', 'UserInvestigationsController')->only('index', 'store', 'update', 'destroy');
Route::get('investigation/edit/', 'UserInvestigationsController@customEdit')->name('investigation.edit');
Route::put('investigation/item/update', 'UserInvestigationsController@customUpdate')->name('investigation.customUpdate');
Route::get('appointments/{appointment}/{action}', 'AppointmentController@showAction')->name('appointments.action');
Route::patch('appointments/{appointment}/force', 'AppointmentController@forceUpdate')->name('appointments.update.force');
Route::resource('prescriptions', 'PrescriptionController');
Route::resource('prescriptions-templates', 'PrescriptionTemplateController')->except('show');
Route::resource('transactions', 'TransactionController')->except('edit');
Route::get('admin/doctor/transactions/index', 'TransactionController@doctorTransactions')->name('doctor.transactions.index');

Route::resource('discounts', 'DiscountController')->middleware('role:master|admin|doctor')->except('create', 'edit', 'show');
Route::resource('badges', 'BadgeController')->except('create', 'edit', 'show');
Route::resource('templates', 'TemplateController')->middleware('role:master|admin');

Route::prefix('sender')->name('sender.')->group(function() {
    // SMS Sender Route
    Route::get('sms', 'SmsController@index')->name('sms');
    Route::post('sms', 'SmsController@send')->name('sms.send');
    // Email Sender Route
    Route::get('email', 'EmailController@index')->name('email');
    Route::post('email', 'EmailController@send')->name('email.send');
});

Route::post('doctor/schedule/manage', 'ScheduleController@scheduleOnOff')->name('doctor.schedule.onoff');
