<?php
/**
 * File "index.php"
 * @author Thomas Bourrely
 * 10/02/2018
 */


/**
 * @TODO : Router à finir
 * @TODO : Autoloader
 * @TODO : Template Engine
 * @TODO : ORM
 */

require_once __DIR__ . '/src/Router/Router.php';
require_once __DIR__ . '/src/Router/Route.php';
require_once __DIR__ . '/src/Router/RouterException.php';

use Pure\Router\Router;

$router = new Router($_GET['url']);

$router->get('/', function() {
    echo "Bienvenue sur ma homepage !";
});

$router->get('/posts/:id', function($id) {
    echo "Voila l'article $id";
});

$router->get('/posts/:id-:slug', function($id, $slug) {
    echo "Article $slug : $id";
})->with('id', '[0-9]+')->with('slug', '[a-z\-0-9]+');







$router->run();