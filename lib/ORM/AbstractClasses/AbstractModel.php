<?php
/**
 * File "Model.php"
 * @author Thomas Bourrely
 * 22/02/2018
 */

namespace Pure\ORM\AbstractClasses;

use Pure\ORM\Classes\EntityCollection;
use Pure\ORM\Interfaces\DatabaseAdapterInterface;


/**
 * @TODO : all() && first()
 */

/**
 * Class Model
 *
 * @package Pure\ORM\Classes
 */
abstract class AbstractModel
{
    /**
     * Primary key used in DB
     *
     * @var string
     */
    protected $primaryKey;

    /**
     * Allowed DB fields
     *
     * @var array
     */
    protected $allowedFields = array();

    /**
     * Model DB table
     *
     * @var string
     */
    protected static $table;

    /**
     * Database connection adapter
     *
     * @var DatabaseAdapterInterface
     */
    protected static $_adapter;

    /**
     * @var bool
     */
    protected $autoIncrement = true;

    /**
     * AbstractEntity constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        foreach ($fields as $name => $value) {
            if (in_array($name, $this->allowedFields)) {
                $this->$name = $value;
            }
            /*if ($value instanceof AbstractProxy) {
                $value = $value->load();
            }*/
        }
    }



    /********************************************************
     * DATABASE ADAPTER
     ********************************************************/

    /**
     * Set DB adapter
     *
     * @param DatabaseAdapterInterface $adapter
     */
    public static function setAdapter(DatabaseAdapterInterface $adapter)
    {
        static::$_adapter = $adapter;
    }

    /**
     * Unset DB adapter
     */
    public static function unsetAdapter()
    {
        static::$_adapter = null;
    }


    /********************************************************
     * CRUD operations
     ********************************************************/


    /**
     * Find entities matching criteria
     *
     * @param string $criteria
     * @return EntityCollection
     */
    public static function where($criteria = "")
    {
        $collection = new EntityCollection();
        $class = get_called_class();

        static::$_adapter->select(static::$table, $criteria);

        while ($data = static::$_adapter->fetch()) {
            $collection->add(null, new $class($data));
        }

        return $collection;
    }

    /**
     * Insert entity
     *
     * @param AbstractModel $entity
     * @return mixed
     */
    public static function insert(AbstractModel $entity)
    {
        return static::$_adapter->insert(static::$table, $entity->toArray());
    }

    /**
     * Update entity
     *
     * @param AbstractModel $entity
     * @return mixed
     */
    public static function update(AbstractModel $entity)
    {
        $pkName = $entity->getPrimaryKey();
        $id = $entity->_get($pkName);
        $data = $entity->toArray();

        return static::$_adapter->update(static::$table, $data, "$pkName = '$id'");
    }

    /**
     * Delete entities matching criteria
     *
     * @param AbstractModel $entity
     * @param string $criteria
     * @return mixed
     */
    public static function delete(AbstractModel $entity)
    {
        $pkName = $entity->getPrimaryKey();
        $id = $entity->_get($pkName);
        return static::$_adapter->delete(static::$table, "$pkName = '$id'");
    }






    /********************************************************
     * ENTITY
     ********************************************************/

    /**
     * Set field's value
     *
     * @param $name
     * @param $value
     */
    protected function _set($name, $value)
    {
        $this->_inAllowedFields($name);

        $mutator = 'set' . ucfirst($name);
        if (method_exists($this, $mutator) && is_callable(array($this, $mutator))) {
            $this->$mutator($value);
        } else {
            $this->$name = $value;
        }
    }

    /**
     * Get field's value
     *
     * @param $name
     * @return mixed|AbstractModel
     */
    protected function _get($name)
    {
        if (!$this->_isset($name)) {
            throw new \InvalidArgumentException("$name has not been set for this entity yet");
        }

        $accessor = 'get' . ucfirst($name);
        if (method_exists($this, $accessor) && is_callable(array($this, $accessor))) {
            return $this->$accessor($name);
        }

        /*if ($field instanceof AbstractProxy) {
            $field = $field->load();
        }*/

        return $this->$name;
    }

    /**
     * Is field's value set
     *
     * @param $name
     * @return bool
     */
    protected function _isset($name)
    {
        $this->_inAllowedFields($name);

        return isset($this->$name);
    }

    /**
     * Unset field
     *
     * @param $name
     * @return bool
     */
    protected function _unset($name)
    {
        $this->_inAllowedFields($name);

        if (isset($this->$name)) {
            unset($this->$name);

            return true;
        }

        return false;
    }

    /**
     * Test if field is allowed
     *
     * @param $name
     */
    protected function _inAllowedFields($name)
    {
        if (!in_array($name, $this->allowedFields)) {
            throw new \InvalidArgumentException("$name not in the allowed fields");
        }
    }

    /**
     * Transform entry into an array
     *
     * @return array
     */
    public function toArray()
    {
        $values = [];
        foreach ($this->allowedFields as $field) {
            $values[$field] = $this->$field;
        }

        if ($this->autoIncrement) {
            unset($values[$this->primaryKey]);
        }

        return $values;
    }

    /**
     * Return entry id value
     *
     * @return mixed|AbstractModel
     */
    public function getId()
    {
        $acc = $this->primaryKey;
        return $this->$acc;
    }

    /**
     * Return entry primary key name
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
}