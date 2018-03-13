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
$router->get('/',\App\Controllers\HomeController::class . ':home' , 'home');

$router->run();
// ----------------------------------------------------------------------------------------