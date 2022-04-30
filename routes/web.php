<?php

use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ListsController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::prefix('api')->group(function () {
    Route::get('/items', [ItemsController::class, 'index' ]);
    Route::get('/items/{id}', [ItemsController::class, 'show']);

    /*
    * Routes for Search
    */
    Route::get('/search/{terms}', [ItemsController::class, 'index']);

    /*
    * Routes for lists
    */
    Route::get('/lists/{name?}', [ListsController::class, 'show']);

    Route::post('/list/{id}/{action?}', [ListsController::class, 'update']);

    /*
    * Routes for the Dashbaord
    */
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::post('/dashboard/cancel/{id}', [DashboardController::class, 'cancel']);

    Route::post('/dashboard/renew/{id}', [DashboardController::class, 'renew']);

    Route::post('/dashboard/{id?}', [DashboardController::class, 'reserve']);

    Route::get('/barcode/{barcode}', [DashboardController::class, 'barcode']);
});

Route::get('/', function () {
    return view('app');
});
