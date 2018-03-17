<?php

use App\Http\Controllers\StravaersController;
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

Route::get('/', function () {
    return view('pages.welcome');
})->name('welcome');

Route::get('/companies', 'StravaersController@loadCompanies')->name('companies');

Route::post('/stravaers', 'StravaersController@store');


Route::get('login/strava', 'Auth\LoginController@redirectToProvider')->name('stravalogin');
Route::get('login/strava/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('leaderboard', 'StravaersController@loadLeaderboard')->name('leaderboard');




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
