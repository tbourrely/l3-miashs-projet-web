<?php

namespace App\Middlewares;

/**
 * File "TestMiddleware.php"
 * @author Thomas Bourrely
 * 06/03/2018
 */
class TestMiddleware
{
    public function __invoke(Callable $next)
    {
        var_dump($_SESSION);
        $next();
    }
}