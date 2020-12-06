<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Router;

class RouteHelper
{
    public static function Resource($resourceName)
    {
        $router = self::getRouter();

        if(Str::contains($resourceName, '\\'))
            $resourceName = Str::lower(Str::afterLast($resourceName, '\\'));

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

    public static function getRouter(){
        return app()->router;
    }

    public static function PublicApi(\Closure $param)
    {
        $router = self::getRouter();

        $router->group(['prefix' => 'api'], function () use ($param){
            $param();
        });
    }

    public static function ProtectedApi(\Closure $param)
    {
        $router = self::getRouter();

        $router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($param){
            $param();
        });
    }

    public static function Auth()
    {
        if(env('USE_AUTH')){
            $router = self::getRouter();

            self::PublicApi(function () use ($router) {
                $router->post('register', [
                    'as' => 'auth.register',
                    'uses' => 'Authcontroller@register'
                ]);
                $router->post('login', [
                    'as' => 'auth.login',
                    'uses' => 'Authcontroller@login'
                ]);
            });

            self::ProtectedApi(function () use ($router) {
                $router->get('profile', [
                    'as' => 'user.profile',
                    'uses' => 'UserController@profile'
                ]);

                $router->post('logout', [
                    'as' => 'auth.logout',
                    'uses' => 'UserController@logout'
                ]);
            });
        }
    }

    public static function Get(string $path)
    {
        return new RouteInstance('get', $path, self::getRouter());
    }

    public static function Put(string $path)
    {
        return new RouteInstance('put', $path, self::getRouter());
    }

    public static function Post(string $path)
    {
        return new RouteInstance('post', $path, self::getRouter());
    }

    public static function Delete(string $path)
    {
        return new RouteInstance('delete', $path, self::getRouter());
    }
}
