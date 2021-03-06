<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('api/login', 'AuthController@login');
$router->get('api/login32', 'AuthController@login32');
$router->post('api/loginold', 'AuthController@loginold');

$router->group(['prefix' => 'api'], function () use ($router) {

    // $router->get('/posts', [PostController::class, 'index']);
    // $router->post('/posts', [PostController::class, 'store']);
    $router->get('/posts', 'PostController@index');
    $router->post('/posts', 'PostController@store');
    $router->put('/posts/{id}', 'PostController@update');
    $router->delete('/posts/{id}', 'PostController@destroy');
});