<?php
/**
 * File "Route.php"
 *
 * @author Thomas Bourrely
 * 10/02/2018
 */

namespace Pure\Router\Classes;

use Pure\Router\Exceptions\RouterException;

/**
 * Class Route
 *
 * @package Pure\Router
 */
class Route
{
    /**
     * Middleware classes instances
     *
     * @var array
     */
    private $middlewareLayers = array();

    /**
     * Position in the layer
     *
     * @var int
     */
    private $middlewareIndex = 0;

    /**
     * Add a middleware to the route
     *
     * @param $middleware
     * @return $this
     */
    public function withMiddleWare($middleware)
    {
        $this->middlewareLayers[] = $middleware;

        return $this;
    }

    /**
     * Return the current middleware, if any
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
     * Route's path
     *
     * @var string
     */
    private $path;

    /**
     * Route's callback
     *
     * @var callable
     */
    private $callable;

    /**
     * Matched params
     *
     * @var array
     */
    private $match_params = [];

    /**
     * Route's params regex
     *
     * @var array
     */
    private $params_regex = [];

    /**
     * Route constructor.
     *
     * @param string $path
     * @param callable $callable
     */
    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * Find params
     *
     * @param string $url
     * @return bool
     */
    public function match($url)
    {
        $url = trim($url, '/');

        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";
        if ( !preg_match($regex, $url, $matches) ) {
            return false;
        }

        array_shift($matches);
        $this->match_params = $matches;

        return true;
    }

    /**
     * Search for a regex for the current match
     *
     * @param string $match
     * @return string
     */
    private function paramMatch($match)
    {
        if ( isset($this->params_regex[$match[1]]) ) {
            return '(' . $this->params_regex[$match[1]] . ')';
        }

        return '([\w]+)';
    }

    /**
     * Call the route's callback
     *
     * @return mixed
     * @throws RouterException
     */
    public function call()
    {
        $middleware = $this->getMiddleware();
        $this->middlewareIndex++;

        // no more middleware
        if (null === $middleware) {
            $callable = null;

            if (is_callable($this->callable)) {
                $callable = $this->callable;
            } elseif (is_string($this->callable)) { // a string with th format : 'className:functionName'
                $exploded = explode(':', $this->callable);

                if (class_exists($exploded[0])) {
                    $classInstance = isset($exploded[0]) ? new $exploded[0] : null;
                    $methodName = isset($exploded[1]) ? $exploded[1]:null;

                    if (isset($classInstance) && method_exists($classInstance, $methodName)) {
                        $callable = [$classInstance, $methodName];
                    }
                }
            }

            if (null === $callable) {
                throw new RouterException('The given callback is not callable');
            }

            return call_user_func_array($callable, $this->match_params);
        }

        // call middleware
        return $middleware([$this, 'call']);
    }

    /**
     * Specify the regex for a param
     *
     * @param string $param
     * @param string $regex
     * @return Route $this
     */
    public function with($param, $regex)
    {
        $this->params_regex[$param] = str_replace('(', '(?:', $regex);

        return $this;
    }

    /**
     * Builds the URL for the current route with the specified params
     *
     * @param array $params
     * @return mixed|string
     */
    public function getUrl($params)
    {
        $path = $this->path;
        foreach ($params as $key => $value) {
            $path = str_replace(":$key", $value, $path);
        }

        return $path;
    }
}