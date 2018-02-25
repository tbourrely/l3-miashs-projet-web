<?php
/**
 * File "ConnectionFactory.php"
 * @author Thomas Bourrely
 * 25/02/2018
 */

namespace App;


use Pure\ORM\Classes\MysqlAdapter;
use Pure\ORM\Exceptions\MysqlAdapterException;

class ConnectionFactory extends MysqlAdapter
{
    private static $config;
    private static $manager;

    public static function setConfig($file)
    {
        if ( file_exists($file) ) {
            static::$config = parse_ini_file($file);
        } else {
            throw new MysqlAdapterException('The config file does not exists');
        }
    }

    public static function makeConnection()
    {
        self::$manager = new MysqlAdapter();
        self::$manager->connect([
            'host'      => self::$config['host'],
            'user'      => self::$config['user'],
            'password'  => self::$config['password'],
            'dbname'    => self::$config['dbname']
        ]);
        self::$manager->bootModel();
    }
}