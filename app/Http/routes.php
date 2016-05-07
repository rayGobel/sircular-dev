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

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['middleware'=>'auth'], function(){

    Route::resource('masterdata/publisher', 'PublisherController');
    Route::resource('masterdata/magazine', 'MagazineController');
    // Necessary for filter/search method
    Route::post('masterdata/edition/begin-filter', 'EditionController@postBeginFilter');
    Route::get('masterdata/edition/filter/{id}', 'EditionController@getFilter');
    Route::resource('masterdata/edition', 'EditionController');

    Route::get('masterdata/agent/relationship/{id}', 'AgentController@getRelationship');
    Route::post('masterdata/agent/create-relationship', 'AgentController@postCreateRelationship');
    Route::resource('masterdata/agent', 'AgentController');
    Route::resource('masterdata/agent-cat', 'AgentCatController');

    Route::resource('circulation/distribution-plan', 'DistributionPlanController');
    Route::resource('circulation/distribution-plan.details', 'DistributionPlanDetController');
    // Necessary for postCreateFromPrev
    Route::post('circulation/create-from-prev', 'DistributionPlanController@postCreateFromPrev');

    Route::get('circulation/distribution-realization/{distRealizationID}/details/{distRealizationDetID}/deliverySpecific', 'DeliveryController@ScopeIndex');
    Route::get('circulation/distribution-realization/{distRealizationID}/compare/{distPlanID}', 'DistributionRealizationController@compare');
    Route::resource('circulation/distribution-realization', 'DistributionRealizationController');
    Route::resource('circulation/distribution-realization.details.delivery', 'DeliveryController', ['except'=>['destroy']] );
    // So that delivery controller are accessible via _typical_ link
    Route::resource('circulation/delivery', 'DeliveryController', ['only'=>['index']]);
    //Route::controller('circulation/return/addEditionDetail', 'ReturnController@postAddEditionDetail');
    Route::resource('circulation/return', 'ReturnController');
    Route::controller('circulation/return', 'ReturnController');

    Route::resource('invoice/invoiceConsign', 'InvoiceConsignController');
    Route::resource('invoice/invoiceQuota', 'InvoiceQuotaController');

    Route::controller('report', 'ReportController');
    Route::resource('group', 'GroupController');

});

// Sircular routing logic
