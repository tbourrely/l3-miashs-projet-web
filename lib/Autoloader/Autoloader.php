<?php
/**
 * File "Autoloader.php"
 * @author Thomas Bourrely
 * 10/02/2018
 */

namespace Pure\Autoloader;

/**
 * Class Autoloader
 *
 * @package Pure\Autoloader
 */
class Autoloader
{
    /**
     * Register autolaoder
     */
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Autoload files from src folder
     *
     * @param string $class
     * @return bool
     */
    static function autoload($class)
    {
        $source_directories = array(
            'lib'
        );

        $file = str_replace(['\\', 'Pure/'], ['/', ''], $class);


        foreach ( $source_directories as $source_dir ) {
            if ( file_exists($source_dir . DIRECTORY_SEPARATOR . $file . '.php') ) {
                require_once $source_dir . DIRECTORY_SEPARATOR . $file . '.php';

                if (class_exists($class)) {
                    return true;
                }
            }
        }

        return false;
    }
}