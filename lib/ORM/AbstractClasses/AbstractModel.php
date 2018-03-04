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
     * CRUD OPERATIONS
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
     * OTHER OPERATIONS
     ********************************************************/

    /**
     * Return all the elements of the table
     *
     * @return array
     */
    public static function all()
    {
        $collection = static::where();
        return $collection->all();
    }

    /**
     * One To One relationship
     *
     * @param $modelName
     * @param $foreignKey
     * @param null $customField
     * @return mixed
     */
    public function hasOne($modelName, $foreignKey, $customField = null)
    {
        return $this->hasMany($modelName, $foreignKey, $customField)->first();
    }

    /**
     * One To One relationship (inverse)
     *
     * @param $modelName
     * @param $foreignKey
     * @param $value
     * @return null|mixed
     */
    public function belongsTo($modelName, $foreignKey, $value)
    {
        static::variablesNotEmpty([$modelName, $foreignKey, $value]);
        static::classExists($modelName);

        if (isset($this->$value)) {
            $value = static::$_adapter->quoteValue($this->$value);
            $where = "$foreignKey = $value";

            return $modelName::where($where)->first();
        }

        return null;
    }

    /**
     * One To Many relationship
     * Returns an EntityCollection
     *
     * @param $modelName
     * @param $foreignKey
     * @param null $customField
     * @return mixed
     */
    public function hasMany($modelName, $foreignKey, $customField = null)
    {
        static::variablesNotEmpty([$modelName, $foreignKey]);
        static::classExists($modelName);

        $key = null;
        if (isset($customField)) {
            if (isset($this->$customField)) {
                $key = $this->$customField;
            }
        }

        if (!isset($key)) {
            $key = $this->getId();
        }

        $key = static::$_adapter->quoteValue($key);

        $where = "$foreignKey = $key";

        return $modelName::where($where);
    }



    /********************************************************
     * HELPERS
     ********************************************************/

    /**
     * Assess $class existence
     *
     * @param $class
     * @throws \Exception
     */
    public static function classExists($class)
    {
        if (!class_exists($class)) {
            throw new \Exception("$class does not exists");
        }
    }

    /**
     * Assess that all variables in $vars are set
     *
     * @param array $params
     * @throws \Exception
     */
    public static function variablesNotEmpty(array $vars)
    {
        foreach ($vars as $var) {
            if (!isset($var)) {
                throw new \Exception('Not set variables not allowed');
            }
        }
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