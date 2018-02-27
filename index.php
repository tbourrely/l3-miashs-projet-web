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
 *
 * Current :
 * # Relations :
 * @TODO : One To One (Inverse) -> belongsTo
 * @TODO : One To Many -> hasMany
 * @TODO : One To Many (Inverse) -> belongsTo
 * @TODO : Many To Many -> belongsToMany
 *
 * # a verifier si existe encore apres refacto
 * @TODO : probleme boucle infinie quand deux entity on un proxy
 *
 *
 * # Next 2 :
 * @TODO : Middleware system
 *
 * # Next 3 :
 * @TODO : passage a PDO
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


$router->get('/', function() {
    echo "Bienvenue sur ma homepage !";
});



$router->get('/clients', function() {
    // test get all : OK
    /*$clients = \App\Model\Client::all();
    var_dump(count($clients));*/

    // test where : OK
    /*$clients = \App\Model\Client::where("nom LIKE 'B%'");
    var_dump(count($clients));*/

    // test first : OK
    /*$clients = \App\Model\Client::where("nom LIKE 'B%'")->first();*/

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

$router->get('/hasOne', function() {
   $client = \App\Model\Client::where()->first();
   $adress = $client->getAdress();
   var_dump($adress->adresse);
});


$router->run();