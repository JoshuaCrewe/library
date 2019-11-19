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
    $router->get('/login/{barcode}', 'BooksController@login');
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/search/{terms}', 'BooksController@search');
});


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/item/{id}', 'BooksController@single');
});

$router->get('/{route:.*}/', function ()  {
    return view('app');
});
