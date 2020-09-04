<?php

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

$router->group(['prefix' => 'api'], function () use ($router) {

    /*
    * Routes for Items
    */
    $router->get('/items', 'ItemsController@index');

    $router->get('/items/{id}', 'ItemsController@show');

    /*
    * Routes for Search
    */
    $router->get('/search/{terms}', 'ItemsController@index');

    /*
    * Routes for lists
    */
    $router->get('lists[/{name}]', 'ListsController@show');

    /*
    * Routes for the Dashbaord
    */
    $router->get('/dashboard', 'DashboardController@index');
});

$router->get('/{route:.*}/', function ()  {
    return view('app');
});
