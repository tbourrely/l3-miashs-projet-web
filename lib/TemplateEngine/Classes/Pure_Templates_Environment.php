<?php
/**
 * File "Pure_Templates_Environment.php"
 * @author Thomas Bourrely
 * 11/02/2018
 */

namespace Pure\TemplateEngine\Classes;

/**
 * Class Pure_Templates_Environment
 *
 * @package Pure\TemplateEngine
 */
class Pure_Templates_Environment
{
    /**
     * The directory containing the templates
     *
     * @var string
     */
    private $templatesDir;

    /**
     * Singleton instance
     *
     * @var null
     */
    private static $_instance = null;

    /**
     * Return an instance of the class
     *
     * @return null
     */
    public static function getInstance()
    {
        if (null === static::$_instance) {
            static::$_instance = new Pure_Templates_Environment();
        }

        return static::$_instance;
    }

    /**
     * Set the path for $templatesDir
     *
     * @param string $directory
     * @return bool
     */
    public function setDirectory($directory)
    {
        if ( is_dir($directory) ) {
            $this->templatesDir = $directory;

            return true;
        }

        return false;
    }

    /**
     * Return the template
     *
     * @param string $name
     * @return Template|null
     */
    public function load($name)
    {
        $name = str_replace('/', DIRECTORY_SEPARATOR, $name);
        $template = $this->templatesDir . DIRECTORY_SEPARATOR . $name . '.php';

        if ( file_exists($template) ) {
            return new Template($template);
        }

        return null;
    }
}