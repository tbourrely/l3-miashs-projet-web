<?php
/**
 * File "index.php"
 * @author Thomas Bourrely
 * 10/02/2018
 */

/**
 * TODOLIST
 * Current :
 * @TODO : Middleware system
 *
 * # Next 3 :
 * @TODO : passage a PDO
 */


/*
 * DOC MIDDLEWARE
 *
 * https://github.com/tbourrely/ccd_berger_bourrely_froehlicher_marlier_wilmouth/blob/master/index.php
 * https://github.com/tbourrely/ccd_berger_bourrely_froehlicher_marlier_wilmouth/blob/master/src/middlewares/AuthMiddleware.php
 * https://www.slimframework.com/docs/v3/concepts/middleware.html
 *
 *
 * Linked list ? : http://www.php.net/manual/en/class.spldoublylinkedlist.php
 */


/*
 * AUTOLOADER
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
\App\ConnectionFactory::setConfig($db_ini);
\App\ConnectionFactory::makeConnection();

/*
 * ROUTER
 */
$router = new Router($_GET['url']);

$router->addMiddleware(new \App\Middlewares\TestMiddleware());

$router->get('/', function() use($ptpl) {
    $ptpl->load('homepage')->render(['title' => 'Pure homepage']);
});

$router->get('/clients', function() {
    // test get all : OK
    $clients = \App\Model\Client::all();
    var_dump(count($clients));

    // test where : OK
    $clients = \App\Model\Client::where("nom LIKE 'B%'");
    var_dump(count($clients));

    // test first : OK
    $clt = \App\Model\Client::where("nom LIKE 'B%'")->first();

    // test insert : OK
    $clt->prenom = 'jeanTest';
    $clt->nom = 'insertTest';
    \App\Model\Client::insert($clt);

    // test update : OK
    $clt->prenom = 'TotoUpdate';
    \App\Model\Client::update($clt);

    // test delete : OK
    \App\Model\Client::delete($clt);
});

$router->get('/hasOne', function() {
   $client = \App\Model\Client::where()->first();
   $adress = $client->getAdress();
   var_dump($adress->adresse);
});

$router->get('/belongsTo', function () {
    $adresse = \App\Model\Adresse::where()->first();
    $client = $adresse->getClient();
    var_dump($client->nom);
});

$router->get('/hasMany', function() {
    $cat = \App\Model\Categorie::where()->first();
    foreach ($cat->getProduits() as $produit) {
        var_dump($produit->nom);
        var_dump($produit->getCat()->nom);
    }
});

$router->get('belongsToMany', function() {
    $clt = \App\Model\Client::where()->first();
    $roles = $clt->getRoles();
    foreach ($roles as $role) {
        var_dump($role->type);
    }

    /**
     * @var $role \App\Model\Role
     */
    $role = $roles[0];
    $clients = $role->getClients();
    if (isset($clients)) {
        foreach ($clients as $client) {
            var_dump($client->nom);
        }
    }
});



$router->run();