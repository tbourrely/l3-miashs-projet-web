<?php

namespace App\Middlewares;

/**
 * File "TestMiddleware.php"
 * @author Thomas Bourrely
 * 06/03/2018
 */
class TestMiddleware
{
    public function __invoke()
    {
        echo '<br><h1>test middleware invoked</h1><br>';
    }
}