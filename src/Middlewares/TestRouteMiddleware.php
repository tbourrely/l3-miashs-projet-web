<?php

namespace App\Middlewares;

/**
 * File "TestRouteMiddleware.php"
 * @author Thomas Bourrely
 * 06/03/2018
 */
class TestRouteMiddleware
{
    public function __invoke(Callable $next)
    {

        if (!isset($_SESSION['connected']) || (isset($_SESSION['connected']) && !$_SESSION['connected'])) {
            var_dump('Your are not connected, pls connect to get access');
            exit();
        }

        $next();
    }
}