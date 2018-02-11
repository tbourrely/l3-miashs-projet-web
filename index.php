<?php
/**
 * File "index.php"
 * @author Thomas Bourrely
 * 10/02/2018
 */


/**
 * @TODO : Template Engine : en cours
 * @TODO : ORM
 */

require __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, '/lib/Autoloader/Autoloader.php');
\Pure\Autoloader\Autoloader::register();

use Pure\Router\Router;
use \Pure\TemplateEngine\Pure_Templates_Environment;

/*
 * Template Engine
 */
$ptpl = new Pure_Templates_Environment();
$ptpl->setDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Templates');

/*
 * ROUTER
 */
$router = new Router($_GET['url']);


$router->get('/', function() {
    echo "Bienvenue sur ma homepage !";
});

$router->get('/posts/:id', function($id) use ($router) {
    echo $router->url('posts.show', ['id' => 'monId']);
    echo '<br>';
    echo "Voila l'article $id";
}, 'posts.show')->with('id', '[0-9]+');

$router->get('/posts/:name', function($name) use ($ptpl, $router) {

    $template = $ptpl->load('post');
    $arrayTest = array(
        'val1',
        'val2',
        'val3'
    );

    $template->render(array('post' => $name, 'url' => $router->url('posts.showByName', ['name' => 'postNameTest']), 'myArray' => $arrayTest, 'testVal' => 0));

}, 'posts.showByName')->with('name', '[^0-9]+');

$router->get('/posts/:id-:slug', function($id, $slug) {
    echo "Article $slug : $id";
})->with('id', '[0-9]+')->with('slug', '[a-z\-0-9]+');


$router->run();