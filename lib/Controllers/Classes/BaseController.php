<?php
/**
 * File "BaseController.php"
 * @author Thomas Bourrely
 * 13/03/2018
 */

namespace Pure\Controllers\Classes;


use Pure\Router\Classes\Router;
use Pure\TemplateEngine\Classes\Pure_Templates_Environment;

/**
 * Class BaseController
 * @package Pure\Controllers\Classes
 */
class BaseController
{
    /**
     * @var null | Pure_Templates_Environment
     */
    private $templateEngine;

    /**
     * @var Router
     */
    private $router;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->templateEngine = Pure_Templates_Environment::getInstance();
        $this->router = Router::getCurrentRouter();
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Render the specified template
     *
     * @param $filename
     * @param array $args
     */
    public function render($filename, array $args = [])
    {
        $template = $this->templateEngine->load($filename);

        if (null !== $template) {
            $template->render($args);
        }

        exit();
    }

    /**
     * Redirect to $url
     *
     * @param $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }
}