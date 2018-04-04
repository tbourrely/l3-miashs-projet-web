<?php
/**
 * File "index.php"
 * @author Thomas Bourrely
 * 10/02/2018
 */


// ----------------------------------------------------------------------------------------
// init sessions
session_start();
// ----------------------------------------------------------------------------------------


// ----------------------------------------------------------------------------------------
/*
 * AUTOLOADER
 */
require __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, '/lib/Autoloader/Autoloader.php');
\Pure\Autoloader\Autoloader::register(array(
    'Pure'  => 'lib',
    'App'   => 'src'
));
// ----------------------------------------------------------------------------------------


// ----------------------------------------------------------------------------------------
use Pure\Router\Classes\Router;
use \Pure\TemplateEngine\Classes\Pure_Templates_Environment;
// ----------------------------------------------------------------------------------------


// ----------------------------------------------------------------------------------------
/*
 * Template Engine
 */
$ptpl = Pure_Templates_Environment::getInstance();

/**
 * @var $ptpl Pure_Templates_Environment
 */
$ptpl->setDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Templates');
// ----------------------------------------------------------------------------------------


// ----------------------------------------------------------------------------------------
/*
 * BDD
 */
$db_ini = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'db.conf.ini';
\App\ConnectionFactory::setConfig($db_ini);
\App\ConnectionFactory::makeConnection();
// ----------------------------------------------------------------------------------------


// ----------------------------------------------------------------------------------------
/*
 * ROUTER
 */
$router = new Router($_GET['url']);

// homepage
$router->get('/',\App\Controllers\HomeController::class . ':home', 'home');

// profil
$router->get('/profil/:id', \App\Controllers\ProfilController::class . ':index', 'profilIndex');



// espace membre
// -> login
$router->get('/login', \App\Controllers\ProfilController::class . ':loginGet', 'loginGET');
$router->post('/login', \App\Controllers\ProfilController::class . ':loginPost', 'loginPOST');

// -> logout
$router->get('/logout', \App\Controllers\ProfilController::class . ':logout', 'logout');

// gestion profil
$router->get('/edit', App\Controllers\ProfilController::class . ':editGet', 'editGET');
$router->post('/edit', App\Controllers\ProfilController::class . ':editPost', 'editPOST');

// gestion animaux
$router->get('/edit/animaux', App\Controllers\ProfilController::class . ':editAnimauxGet', 'editAnimauxGET');
$router->get('edit/animaux/add', App\Controllers\ProfilController::class . ':addAnimauxGet', 'addAnimauxGET');

$router->run();
// ----------------------------------------------------------------------------------------