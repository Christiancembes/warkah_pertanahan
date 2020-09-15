<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'api'], function() {
	Route::post('login', '\App\Http\Controllers\API\UserController@login');
	Route::post('register', '\App\Http\Controllers\API\UserController@register');

	Route::post('archives-issues/report', '\App\Http\Controllers\API\ArchivesIssuesController@report');

	Route::group(['middleware' => 'jwt-auth'], function() {
		Route::get('me', '\App\Http\Controllers\API\UserController@me');
		Route::put('me', '\App\Http\Controllers\API\UserController@update');
		Route::get('counter', '\App\Http\Controllers\API\CounterController@index');
		Route::post('upload', '\App\Http\Controllers\API\ImageUploadController@process');
		Route::resource('archives-locations', '\App\Http\Controllers\API\ArchivesLocationsController');
		Route::resource('archives', '\App\Http\Controllers\API\ArchivesController');
		Route::get('archives-issues/search/{keyword}', '\App\Http\Controllers\API\ArchivesIssuesController@search');
		Route::resource('archives-issues', '\App\Http\Controllers\API\ArchivesIssuesController');
		Route::resource('employees', '\App\Http\Controllers\API\EmployeesController');
	});
});