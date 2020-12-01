<?php

namespace App\Helpers;

use Laravel\Lumen\Routing\Router;

class RouteHelper
{
    public static function Resource(Router $router, $resourceName)
    {
        $router->get($resourceName, [
            'as' => $resourceName . '.index',
            'uses' => ucfirst($resourceName) . 'Controller@index'
        ]);
        $router->get($resourceName . '/create', [
            'as' => $resourceName . '.create',
            'uses' => ucfirst($resourceName) . 'Controller@create'
        ]);
        $router->get("$resourceName/{id}/edit", [
            'as' => $resourceName . '.edit',
            'uses' => ucfirst($resourceName) . 'Controller@edit'
        ]);
        $router->get($resourceName . "/{id}", [
            'as' => $resourceName . '.show',
            'uses' => ucfirst($resourceName) . 'Controller@show'
        ]);
        $router->post($resourceName, [
            'as' => $resourceName . '.store',
            'uses' => ucfirst($resourceName) . 'Controller@store'
        ]);
        $router->put($resourceName . "/{id}", [
            'as' => $resourceName . '.update',
            'uses' => ucfirst($resourceName) . 'Controller@update'
        ]);
        $router->delete($resourceName . "/{id}", [
            'as' => $resourceName . '.destroy',
            'uses' => ucfirst($resourceName) . 'Controller@destroy'
        ]);
    }

}
