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

Route::group(['prefix' => 'api'], function () {

	Route::get('/register','ApiController@apiRegisterView');

	Route::post('/registeration','ApiController@apiRegister');

	Route::post('/login','ApiController@apiLogin');

	Route::post('/logout','ApiController@apiLogout');

	Route::post('/shorten','ApiController@apiShorten');

	Route::get('/home','ApiController@apiHome');

	Route::get('/regenerate','ApiController@apiKeyRegenerate');

	Route::post('/action','ApiController@actionApi');

	Route::post('/{hash}/page={currentPage}',['as'=>'apistate','uses'=>'ApiController@actionDashboard']);
	
	Route::get('/home/page={i}',['as'=>'apipaginate','uses'=>'ApiController@paginatedView']);

});

/*
 * Routes the control to dashboard 
 */
Route::get('/dashboard','LoginController@dashboard');

/*
 * Routes the control to first page of dashboard 
 */
Route::get('/home/page={i}',['as'=>'paginate','uses'=>'LinkController@paginatedView']);

/*
 * Routes the control to currentpage after enable or disable action 
 */
Route::post('/{hash}/page={currentPage}',['as'=>'normalstate','uses'=>'LinkController@action']);

/*
 * Routes the control to login view page 
 */
Route::get('/login','LoginController@home');

/*
 * Routes the control to authenticate the user 
 */
Route::post('/login/home','LoginController@auth');

/*
 * Routes the control to logout 
 */
Route::get('home/logout','LoginController@signout');

/*
 * Routes the control to shorten the url 
 */
Route::post('/make','LinkController@createCode');

/*
 * Routes the control to register for api
 */
Route::post('/register','LoginController@register');

/*
 * Routes the control to signup view 
 */
Route::get('/signup','LoginController@registerView');

/*
 * Routes the control to the shortened link 
 */
Route::get('/{hash}',['as'=>'web','uses'=>'LinkController@redirect']);

?>
