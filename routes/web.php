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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// change my locale
Route::get('/custom-locale/change/{locale}','CT_Controller@changeLocale');

Route::group(['prefix' => 'translation'],function(){
	route::get('','CT_Controller@index');

	route::get('locales','CT_Controller@showLocales');
	route::get('/add/locale','CT_Controller@addLocale');
	route::post('/add/locale','CT_Controller@createLocale');
	route::get('/edit/locale/{locale}','CT_Controller@editLocale');
	route::get('/delete/locale/{locale}','CT_Controller@deleteLocale');

	
	route::get('/add/key','CT_Controller@addTransKey');
	route::post('/add/key','CT_Controller@storeTransKey');

	route::get('/edit/key/{id}','CT_Controller@editTransKey');
	route::post('/update/key','CT_Controller@updateTransKey');

	route::get('/delete/key/{id}','CT_Controller@deleteTransKey');

	route::get('/sync','CT_Controller@syncroniseTranslation');

});

Route::get('login/github', 'socialLoginController@redirectToProvider');
Route::get('login/github/callback', 'socialLoginController@handleProviderCallback');