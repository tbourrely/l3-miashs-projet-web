<?php

/**
 * File "Router.php"
 *
 * @author Thomas Bourrely
 * 10/02/2018
 */

namespace Pure\Router;

/**
 * Class Router
 *
 * @package Pure\Router
 */
class Router
{
    /**
     * Current URL
     *
     * @var string
     */
    private $url;

    /**
     * List of routes
     *
     * @var array Route
     */
    private $routes = array();

    /**
     * List of named routes
     *
     * @var array Route
     */
    private $namedRoutes = array();

    /**
     * Middleware layers
     *
     * @var array
     */
    private $middlewareLayers = array();

    /**
     * Index of the current middleware
     *
     * @var int
     */
    private $middlewareIndex = 0;

    /**
     * Router constructor.
     *
     * @param $url string
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Add a 'GET' route
     *
     * @param string $path
     * @param callable $callable
     * @param null|string $name
     * @return Route
     */
    public function get($path, $callable, $name = null)
    {
        return $this->addRoute($path, $callable, 'GET', $name);
    }

    /**
     * Add a 'POST' route
     *
     * @param string $path
     * @param callable $callable
     * @param null|string $name
     * @return Route
     */
    public function post($path, $callable, $name = null)
    {
        return $this->addRoute($path, $callable, 'POST', $name);
    }

    /**
     * Add a $method route
     *
     * @param string $path
     * @param callable $callable
     * @param string $method
     * @param string $name
     * @return Route
     */
    private function addRoute($path, $callable, $method, $name)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        if ( $name ) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }

    /**
     * Get the URL for a named route with srouteParams parameters
     *
     * @param string $name
     * @param string[] $routeParams
     * @return string
     * @throws RouterException
     */
    public function url($name, $routeParams = [])
    {
        if ( !isset($this->namedRoutes[$name]) ) {
            throw new RouterException('No routes exists with this name');
        }

        return $this->namedRoutes[$name]->getUrl($routeParams);
    }

    /**
     * Run the application
     */
    public function run()
    {
        $this->runMiddlewares();
    }

    /**
     * Add a middleware
     *
     * @param $middleware
     */
    public function addMiddleware($middleware)
    {
        if (!in_array($middleware, $this->middlewareLayers)) {
            $this->middlewareLayers[] = $middleware;
        }
    }

    /**
     * Start a recursive iteration through the middleware layers
     *
     * @return mixed
     */
    private function runMiddlewares()
    {
        $middleware = $this->getMiddleware();
        $this->middlewareIndex++;
        if (null === $middleware) {
            $this->runRoute();
        } else {
            return $middleware([$this, 'run']);
        }
    }

    /**
     * Get the current layer
     *
     * @return mixed|null
     */
    private function getMiddleware()
    {
        if (isset($this->middlewareLayers[$this->middlewareIndex])) {
            return $this->middlewareLayers[$this->middlewareIndex];
        }

        return null;
    }

    /**
     * Find the matching route
     *
     * @return mixed
     * @throws RouterException
     */
    private function runRoute()
    {
        if ( !isset($this->routes[$_SERVER['REQUEST_METHOD']]) ) {
            throw new RouterException('No route for this REQUEST_METHOD');
        }

        /**
         * @var Route $route
         */
        foreach ( $this->routes[$_SERVER['REQUEST_METHOD']] as $route ) {
            if ( $route->match($this->url) ) {
                return $route->call();
            }
        }

        throw new RouterException('No route found');
    }
}