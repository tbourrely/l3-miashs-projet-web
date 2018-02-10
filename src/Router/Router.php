<?php
/**
 * File "Router.php"
 * @author Thomas Bourrely
 * 10/02/2018
 */

namespace Pure\Router;


class Router
{
    private $url;
    private $routes = array();

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function get($path, $callable)
    {
        return $this->add($path, $callable, 'GET');
    }

    public function post($path, $callable)
    {
        return $this->add($path, $callable, 'POST');
    }

    private function add($path, $callable, $method)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        return $route;
    }

    public function run()
    {
        if ( !isset($this->routes[$_SERVER['REQUEST_METHOD']]) ) {
            throw new RouterException('No route for this REQUEST_METHOD');
        }

        foreach ( $this->routes[$_SERVER['REQUEST_METHOD']] as $route ) {
            if ( $route->match($this->url) ) {
                return $route->call();
            }
        }

        throw new RouterException('No route found');
    }
}