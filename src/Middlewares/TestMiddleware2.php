<?php

namespace App\Middlewares;

/**
 * File "TestMiddleware2.php"
 * @author Thomas Bourrely
 * 06/03/2018
 */
class TestMiddleware2
{
    public function __invoke(Callable $next)
    {
        echo '<br><h1>Second test middleware invoked</h1><br>';
        $next();
    }
}