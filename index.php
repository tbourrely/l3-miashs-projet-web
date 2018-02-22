<?php
/**
 * File "index.php"
 * @author Thomas Bourrely
 * 10/02/2018
 */


/*
 * # Docs
 *
 * Eloquent doc
 * https://laravel.com/docs/5.6/eloquent-relationships
 *
 * Projet giftbox (exemple model)
 * https://github.com/tbourrely/giftbox/blob/master/src/giftbox/models/Categorie.php
 */



/**
 * Current :
 * @TODO : DB as global propre
 *
 * Next 1 :
 * # Relations :
 * @TODO : One To One
 * @TODO : One To Many
 * @TODO : One To Many (Inverse)
 * @TODO : Many To Many
 *
 * # a verifier si existe encore apres refacto
 * @TODO : probleme boucle infinie quand deux entity on un proxy
 *
 *
 * # Next 2 :
 * @TODO : Middleware system
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
 * BDD
 */
$db_ini = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'db.conf.ini';
$mysqlAdapter = new \Pure\ORM\Classes\MysqlAdapter($db_ini);


/*
 * ROUTER
 */
$router = new Router($_GET['url']);


$router->get('/', function() {
    echo "Bienvenue sur ma homepage !";
});



$router->get('/clients', function() use($mysqlAdapter) {
    \Pure\ORM\AbstractClasses\AbstractModel::setAdapter($mysqlAdapter);

    // test get all : OK
    /*$clients = \App\Model\Client::all();
    var_dump(count($clients));*/

    // test where : OK
    /*$clients = \App\Model\Client::where("nom LIKE 'B%'");
    var_dump(count($clients));*/

    // test first : OK
    /*$clients = \App\Model\Client::where("nom LIKE 'B%'")->first();
    var_dump(count($clients->nom));*/


    // test insert : OK
    /*$clt->prenom = 'jeanTest';
    $clt->nom = 'insertTest';
    \App\Model\Client::insert($clt);*/

    // test update : OK
    /*$clt->prenom = 'TotoUpdate';
    \App\Model\Client::update($clt);*/

    // test delete : OK
    /*\App\Model\Client::delete($clt);*/
});










// # OLD TESTS

/*$router->get('/posts/:id', function($id) use ($router) {
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
})->with('id', '[0-9]+')->with('slug', '[a-z\-0-9]+');*/

/*$router->get('/client', function() use($mysqlAdapter) {
    $clientMapper = new \App\Mapper\ClientMapper($mysqlAdapter);

    $client = $clientMapper->findById(1);
    if (isset($client)) {
        var_dump('findById : ' . $client->nom);
    } else {
        var_dump('error get client by id');
    }

    $clientCollection = $clientMapper->find();
    if (isset($clientCollection)) {
        foreach ($clientCollection->getIterator() as $client) {
            var_dump('find : ' . $client->nom);
        }
    } else {
        var_dump('error getting client collection');
    }
});*/

/*$router->get('/produits/:id', function($id) use ($mysqlAdapter) {
    $clientMapper = new \App\Mapper\ClientMapper($mysqlAdapter);
    $produitMapper = new \App\Mapper\ProduitMapper($mysqlAdapter, $clientMapper);

    $produit = $produitMapper->findById($id);

    echo "<h1>$produit->nom</h1>";
    echo "<h2>prix : $produit->prix €</h2>";
    echo "<h2>Client : " . $produit->client->nom . " " . $produit->client->prenom . "</h2>";

});*/

/*$router->get('/produits/clt/:id', function($id) use($mysqlAdapter) {
    $clientMapper = new \App\Mapper\ClientMapper($mysqlAdapter);
    $produitMapper = new \App\Mapper\ProduitMapper($mysqlAdapter);

    $clientMapper->setMapper('_productMapper', $produitMapper);
    $produitMapper->setMapper('_clientMapper', $clientMapper);

    $client = $clientMapper->findById($id);

    foreach ($client->produits->getIterator() as $produit) {
        var_dump($produit->nom);
    }
});*/

$router->run();