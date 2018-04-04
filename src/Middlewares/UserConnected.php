<?php

/**
 * File "UserConnected.php"
 * @author Thomas Bourrely
 * 04/04/2018
 */

namespace App\Middlewares;

use Pure\Controllers\Classes\BaseController;
use Pure\Router\Classes\Router;

class UserConnected
{
    public function __invoke(Callable $next)
    {
        if (!isset($_SESSION['logged_in'], $_SESSION['user'])) {
            $router = Router::getCurrentRouter();
            $baseController = new BaseController();
            $baseController->redirect($router->url('loginGET'));
        }

        $next();
    }
}