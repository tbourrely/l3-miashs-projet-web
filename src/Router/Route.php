<?php
/**
 * File "Route.php"
 * @author Thomas Bourrely
 * 10/02/2018
 */

namespace Pure\Router;


class Route
{
    private $path;
    private $callable;
    private $match_params = [];
    private $params_regex = [];


    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

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

    private function paramMatch($match)
    {
        if ( isset($this->params_regex[$match[1]]) ) {
            return '(' . $this->params_regex[$match[1]] . ')';
        }

        return '([\w]+)';
    }

    public function call()
    {
        return call_user_func_array($this->callable, $this->match_params);
    }

    public function with($param, $regex)
    {
        $this->params_regex[$param] = $regex;

        return $this;
    }
}