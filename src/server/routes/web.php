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

use App\Models\Permission;
use App\Models\User;
use Laravel\Lumen\Routing\Router as DefaultRouter;
use App\Helpers\RouteHelper as Router;

/** @var DefaultRouter $router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

Router::Auth(); // Add Auth routes only if needed

Router::PublicApi(function () use($router){
    //Router::Get('/s/{id}')->As('ss')->Calls(\App\Http\Controllers\UserController::class, 'show');
    $router->get('/something-public', function (){
        return 'This is Public';
    });
});

Router::ProtectedApi(function () use ($router){
    $router->get('/something-private', function (){
        return 'This is Private';
    });

    Router::Resource(User::class);
});
