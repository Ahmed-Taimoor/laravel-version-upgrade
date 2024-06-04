<?php

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

Route::get('/','TransactionController@checkout');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::post('transaction', 'TransactionController@store')->name('transaction');
Route::post('verify-discount-code', 'DiscountOffersControllers@verifyDiscountCode')->name('verify-discount-code');

Route::get('thank-you', 'HomeController@thankYouPage');

Route::get('country/{id}/states','HomeController@countryStates');

Route::group(['middleware'=>'auth'],function (){
    Route::get('home', 'HomeController@index')->name('home');

    Route::resource('discount-offers', 'DiscountOffersControllers');
    Route::get('transactions', 'TransactionController@index')->name('transactions');
});