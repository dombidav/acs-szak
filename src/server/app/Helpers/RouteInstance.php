<?php


namespace App\Helpers;


use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Router;

class RouteInstance
{
    /**
     * @var string
     */
    private $uri;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Router
     */
    private $router;
    private $method;

    /**
     * RouteInstance constructor.
     * @param string $method
     * @param string $uri
     * @param Router $router
     */
    public function __construct(string $method, string $uri, Router $router)
    {
        $this->uri = $uri;
        $this->router = $router;
        $this->method = $method;
    }

    public function As(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function Calls($target, $function = null)
    {
        $m = $this->method;
        if($function)
            $this->router->$m($this->uri, [
                'as' => $this->name,
                'uses' => (Str::contains($target, '\\') ? Str::afterLast($target, '\\') : $target) . '@' . $function
            ]);
        else
            $this->router->$m($this->uri, $target);
    }
}
