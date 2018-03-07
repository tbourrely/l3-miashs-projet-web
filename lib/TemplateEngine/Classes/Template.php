<?php
/**
 * File "Template.php"
 * @author Thomas Bourrely
 * 11/02/2018
 */

namespace Pure\TemplateEngine\Classes;

/**
 * Class Template
 *
 * @package Pure\TemplateEngine
 */
class Template
{
    /**
     * Template path
     *
     * @var string
     */
    private $path;

    /**
     * Template constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Render the template
     *
     * @param array $params
     */
    public function render($params = array())
    {
        ob_start();
        extract($params, EXTR_SKIP);
        require_once $this->path;
        $content = ob_get_clean();

        echo $content;
    }
}