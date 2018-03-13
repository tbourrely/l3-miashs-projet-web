<?php
/**
 * File "BaseController.php"
 * @author Thomas Bourrely
 * 13/03/2018
 */

namespace Pure\Controllers\Classes;


use Pure\TemplateEngine\Classes\Pure_Templates_Environment;

class BaseController
{
    /**
     * @var null | Pure_Templates_Environment
     */
    private $templateEngine;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->templateEngine = Pure_Templates_Environment::getInstance();
    }

    /**
     * Render the specified template
     *
     * @param $filename
     * @param array $args
     */
    public function render($filename, array $args)
    {
        $template = $this->templateEngine->load($filename);

        if (null !== $template) {
            $template->render($args);
        }

        exit();
    }
}