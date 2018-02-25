<?php
/**
 * File "ConnectionFactory.php"
 * @author Thomas Bourrely
 * 25/02/2018
 */

namespace App;

use Pure\ORM\Classes\ConnectionManager;

/**
 * Class ConnectionFactory
 *
 * @package App
 */
class ConnectionFactory
{
    /**
     * Config
     *
     * @var
     */
    private static $config;

    /**
     * connection manager
     *
     * @var ConnectionManager
     */
    private static $connectionManager;

    /**
     * Load config
     *
     * @param $file
     * @throws \Exception
     */
    public static function setConfig($file)
    {
        if ( file_exists($file) ) {
            static::$config = parse_ini_file($file);
        } else {
            throw new \Exception('The config file does not exists');
        }
    }

    /**
     * Create a DB connection
     */
    public static function makeConnection()
    {
        self::$connectionManager = new ConnectionManager();
        self::$connectionManager->addConnection([
            'host'      => self::$config['host'],
            'user'      => self::$config['user'],
            'password'  => self::$config['password'],
            'dbname'    => self::$config['dbname']
        ]);
        self::$connectionManager->bootModel();
    }
}