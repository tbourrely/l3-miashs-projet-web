<?php
/**
 * File "index.php"
 * @author Thomas Bourrely
 * 10/02/2018
 */


/**
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

$router->get('/posts/:id', function($id) use ($router) {
    echo $router->url('posts.show', ['id' => 'monId']);
    echo '<br>';
    echo "Voila l'article $id";
}, 'posts.show');

$router->get('/posts/:id-:slug', function($id, $slug) {
    echo "Article $slug : $id";
})->with('id', '[0-9]+')->with('slug', '[a-z\-0-9]+');


$router->run();