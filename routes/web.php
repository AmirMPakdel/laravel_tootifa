<?php

use App\Http\Middleware\EnsureUserTokenIsValid;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

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

//********************************************************MAIN***********************************************************

// Main user routes
Route::group([
    'prefix' => 'api/main',
    'namespace'  => 'App\Http\Controllers\API',
], function () {
    Route::post('/user/checkphonenumber', 'Registration\UserRegistrationController@checkPhoneNumber');
    Route::post('/user/login', 'Registration\UserRegistrationController@loginWithPassword');
    Route::post('/user/verificationcode/send', 'Registration\UserRegistrationController@sendVerificationCode');
    Route::post('/user/verificationcode/check', 'Registration\UserRegistrationController@checkVerificationCode');
    Route::post('/user/register', 'Registration\UserRegistrationController@completeRegistraion');
});

//********************************************************TENANT*********************************************************

// Tenant user routes
Route::group([
    'prefix' => 'api/tenant',
    'namespace'  => 'App\Http\Controllers\API',
    'middleware' => [ EnsureUserTokenIsValid::class, InitializeTenancyByRequestData::class],
], function () {
    Route::post('/user/courses/create', 'UserDashboard\Courses\CoursesController@createCourse');
    Route::post('/user/courses/fetch', 'UserDashboard\Courses\CoursesController@fetchCourses');
    Route::post('/user/course/edit/logo', 'UserDashboard\Courses\CourseEditController@editCourseLogo');
    Route::post('/user/course/edit/cover', 'UserDashboard\Courses\CourseEditController@editCourseCover');
    Route::post('/user/course/edit/title', 'UserDashboard\Courses\CourseEditController@editCourseTitle');
    Route::post('/user/course/edit/title', 'UserDashboard\Courses\CourseEditController@editCourseTitle');
});

// Tenant public routes
Route::group([
    'prefix' => 'api/tenant',
    'namespace'  => 'App\Http\Controllers\API',
    'middleware' => [ InitializeTenancyByRequestData::class],
], function () {
    Route::get('public/course/{id}/logo', 'UserDashboard\Courses\CoursesController@getLogo');
    Route::get('public/course/{id}/cover', 'UserDashboard\Courses\CoursesController@getCover');
});




