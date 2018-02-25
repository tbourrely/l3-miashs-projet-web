<?php
/**
 * File "ConnectionManager.php"
 * @author Thomas Bourrely
 * 25/02/2018
 */

namespace Pure\ORM\Classes;

use Pure\ORM\AbstractClasses\AbstractModel;

/**
 * Class ConnectionManager
 *
 * @package Pure\ORM\Classes
 */
class ConnectionManager
{
    /**
     * Database instance
     *
     * @var MysqlAdapter
     */
    protected $_manager;

    /**
     * Create a connection
     *
     * @param array $config
     * @throws \Exception
     */
    public function addConnection(array $config)
    {
        $manager = new MysqlAdapter();

        if (!$manager->connect($config)) {
            throw new \Exception('Database connection failed');
        }

        $this->_manager = $manager;
    }

    /**
     * Set the adapter for the abstract model
     */
    public function bootModel()
    {
        AbstractModel::setAdapter($this->_manager);
    }

    /**
     * Manager getter
     *
     * @return MysqlAdapter
     */
    public function getManager()
    {
        return $this->_manager;
    }
}