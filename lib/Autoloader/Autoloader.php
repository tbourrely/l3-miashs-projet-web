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
     * @var array source list
     */
    private static $sourceList;

    /**
     * Register autolaoder
     */
    static function register(array $sources)
    {
        static::$sourceList = $sources;

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
        $type = explode('\\', $class)[0];

        if ( isset(static::$sourceList[$type]) ) {

            // Add a trailing slash to the base namespances
            $replaceListKeys = array_merge(['\\'], array_map(function ($source) {
                return $source . '/';
            }, array_keys(static::$sourceList)));
            $replaceListValues = array_merge(['/'], array_fill(0, count(static::$sourceList), ''));

            $file = str_replace($replaceListKeys, $replaceListValues, $class);

            $sourceDir = static::$sourceList[$type];
            $file = $sourceDir . DIRECTORY_SEPARATOR . $file . '.php';

            if ( file_exists($file) ) {
                require_once $file;

                if (class_exists($class)) {
                    return true;
                }
            }

        }

        return false;
    }
}