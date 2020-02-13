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

// $router->group(['prefix' => 'api'], function () use ($router) {
//     $router->get('/login/{barcode}', 'BooksController@login');
// });
//
// $router->group(['prefix' => 'api'], function () use ($router) {
//     $router->get('/lists', 'BooksController@lists');
// });
//
// $router->group(['prefix' => 'api'], function () use ($router) {
//     $router->get('/dashboard', 'BooksController@login');
// });
//
// $router->group(['prefix' => 'api'], function () use ($router) {
//     $router->get('/search/{terms}', 'BooksController@search');
// });
//
//
// $router->group(['prefix' => 'api'], function () use ($router) {
//     $router->get('/item/{id}', 'BooksController@single');
// });

/*
* Routes for Items
*/
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/items', 'ItemsController@index');
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/items/{id}', 'ItemsController@show');
});

/*
* Routes for Search
*/
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/search/{terms}', 'ItemsController@index');
});

/*
* Routes for lists
*/
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/lists/{list}', 'ListsController@show');
});

/*
* Routes for lists
*/
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/lists/{list}', 'ListsController@show');
});

/*
* Routes for logins
*/
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/dashboard', 'DashboardController@index');
});


$router->get('/{route:.*}/', function ()  {
    return view('app');
});
