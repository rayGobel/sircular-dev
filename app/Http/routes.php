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

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['middleware'=>'auth'], function(){

    Route::resource('masterdata/publisher', 'PublisherController');
    Route::resource('masterdata/magazine', 'MagazineController');
    //Route::controller('masterdata/edition', 'EditionController');
    Route::resource('masterdata/edition', 'EditionController');
    Route::resource('masterdata/agent', 'AgentController');
    Route::resource('masterdata/agent-cat', 'AgentCatController');
    Route::resource('circulation/distribution-plan', 'DistributionPlanController');
    Route::resource('circulation/distribution-plan.details', 'DistributionPlanDetController');
    Route::get('circulation/distribution-plan/{distPlanID}/details/{distPlanDetID}/deliverySpecific', 'DeliveryController@ScopeIndex');
    Route::resource('circulation/distribution-plan.details.delivery', 'DeliveryController', ['except'=>['destroy']] );
    // So that delivery controller are accessible via _typical_ link
    Route::resource('circulation/delivery', 'DeliveryController', ['only'=>['index', 'create']]);
    Route::post('circulation/return/addEditionDetail', 'ReturnController@postAddEditionDetail');
    Route::resource('circulation/return', 'ReturnController');
    Route::resource('invoice/invoice', 'InvoiceController');

});

// Sircular routing logic
