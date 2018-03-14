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

$router->get('/', 'MainController@home');

$router->get('/login-callback', 'MainController@callback');
$router->get('/logout', 'MainController@logout');
$router->get('/profile/facebook/{id}', 'MainController@profile');
