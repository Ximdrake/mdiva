<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.
Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('maintenance', 'MaintenanceCrudController');
    Route::crud('patients', 'PatientsCrudController');
    Route::crud('roles', 'RolesCrudController');
    Route::post('maintenance/send/{id}', 'MaintenanceCrudController@send');
    Route::post('maintenance/check/{id}', 'MaintenanceCrudController@check');
    Route::get('/user-history/{id}', 'HistoryCrudController@userHistory');
    Route::crud('history', 'HistoryCrudController');
}); // this should be the absolute last line of this file