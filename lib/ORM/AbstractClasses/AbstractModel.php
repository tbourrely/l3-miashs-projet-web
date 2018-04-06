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
    protected static $primaryKey;

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
        $pkName = $entity::getPrimaryKey();
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
        $pkName = $entity::getPrimaryKey();
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
     * @param null $localKey
     * @return mixed
     */
    public function hasOne($modelName, $foreignKey, $localKey = null)
    {
        return $this->hasMany($modelName, $foreignKey, $localKey)->first();
    }

    /**
     * One To One relationship (inverse)
     *
     * @param $modelName
     * @param $foreignKey
     * @param $localKey
     * @return mixed|null
     */
    public function belongsTo($modelName, $foreignKey, $localKey)
    {
        static::variablesNotEmpty([$modelName, $foreignKey, $localKey]);
        static::classExists($modelName);

        if (isset($this->$localKey)) {
            $localKey = static::$_adapter->quoteValue($this->$localKey);
            $where = "$foreignKey = $localKey";

            /**
             * @var $modelName AbstractModel
             */
            return $modelName::where($where)->first();
        }

        return null;
    }

    /**
     * One To Many relationship
     *
     * @param $modelName
     * @param $foreignKey
     * @param null $localKey
     * @return EntityCollection
     */
    public function hasMany($modelName, $foreignKey, $localKey = null)
    {
        static::variablesNotEmpty([$modelName, $foreignKey]);
        static::classExists($modelName);

        $key = null;
        if (isset($localKey)) {
            if (isset($this->$localKey)) {
                $key = $this->$localKey;
            }
        }

        if (!isset($key)) {
            $key = $this->getId();
        }

        $key = static::$_adapter->quoteValue($key);

        $where = "$foreignKey = $key";

        /**
         * @var $modelName AbstractModel
         */
        return $modelName::where($where);
    }

    /**
     * Many To Many relationship
     *
     * @param $modelName
     * @param $pivotTable
     * @param $modelForeignKey
     * @param $targetForeignKey
     * @return null|EntityCollection
     */
    public function belongsToMany($modelName, $pivotTable, $modelForeignKey, $targetForeignKey)
    {
        static::variablesNotEmpty([$modelName, $pivotTable, $modelForeignKey, $targetForeignKey]);
        static::classExists($modelName);

        $modelPk = static::$_adapter->quoteValue($this->getId());
        $wherePivot = "$modelForeignKey = $modelPk";

        $rows = static::$_adapter->select($pivotTable, $wherePivot);

        if ($rows) {

            $targetList = array();
            while ($data = static::$_adapter->fetch()) {
                if (isset($data[$targetForeignKey])) {
                    $targetList[] = $data[$targetForeignKey];
                }
            }

            /**
             * @var $modelName AbstractModel
             */
            $targetPk = $modelName::getPrimaryKey();
            $where = "$targetPk IN (" . implode(',', $targetList) . ')';

            return $modelName::where($where);
        }

        return null;
    }

    /********************************************************
     * HELPERS
     ********************************************************/

    /**
     * Return the MAX Primary Key
     *
     * @param string $criteria
     * @return mixed
     * @throws \Exception
     */
    public static function maxPk($criteria = "")
    {
        if (!isset(static::$primaryKey)) {
            throw new \Exception('Primary key must be set');
        }

        $fields = 'MAX(' . static::$primaryKey . ')';

        static::$_adapter->select(static::$table, $criteria, $fields, '', 1);

        $data = static::$_adapter->fetch();

        return isset($data[$fields]) ? $data[$fields] : $data;
    }

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

            if (!isset($this->$field) && $this->autoIncrement && $field === static::$primaryKey)
                continue;

            $values[$field] = $this->$field;
        }

        if ($this->autoIncrement) {
            unset($values[static::$primaryKey]);
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
        $acc = static::$primaryKey;
        return $this->$acc;
    }

    /**
     * Return entry primary key name
     *
     * @return string
     */
    public static function getPrimaryKey()
    {
        return static::$primaryKey;
    }
}