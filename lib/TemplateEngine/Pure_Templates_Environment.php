<?php
/**
 * File "Pure_Templates_Environment.php"
 * @author Thomas Bourrely
 * 11/02/2018
 */

namespace Pure\TemplateEngine;

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
     * @return Template
     * @throws Pure_Templates_Exception
     */
    public function load($name)
    {
        $name = str_replace('/', DIRECTORY_SEPARATOR, $name);
        $template = $this->templatesDir . DIRECTORY_SEPARATOR . $name . '.php';

        if ( file_exists($template) ) {
            return new Template($template);
        }

        throw new Pure_Templates_Exception('No such template file');
    }
}