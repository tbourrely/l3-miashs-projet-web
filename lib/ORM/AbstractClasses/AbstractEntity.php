<?php
/**
 * File "AbstractEntity.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\AbstractClasses;
use Pure\ORM\Classes\EntityProxy;

/**
 * Class AbstractEntity
 *
 * @package Pure\ORM\AbstractClasses
 */
abstract class AbstractEntity
{
    /**
     * primary key used in DB
     *
     * @var string
     */
    public static $_primaryKey;

    /**
     * Columns from DB (depending of allowedFields)
     *
     * @var array
     */
    protected $_values = array();

    /**
     * Allowed DB fields
     *
     * @var array
     */
    protected $_allowedFields = array();

    /**
     * AbstractEntity constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        foreach ($fields as $name => $value) {
            if ($value instanceof AbstractProxy) {
                $value = $value->load();
            }

            $this->$name = $value;
            $this->_values[$name] = $value;
        }
    }

    /**
     * Set field's value
     *
     * @param $name
     * @param $value
     */
    public function _set($name, $value)
    {
        $this->_inAllowedFields($name);

        $mutator = 'set' . ucfirst($name);
        if (method_exists($this, $mutator) && is_callable(array($this, $mutator))) {
            $this->$mutator($value);
        } else {
            $this->_values[$name] = $value;
            $this->$name = $value;
        }
    }

    /**
     * Get field's value
     *
     * @param $name
     * @return mixed|AbstractEntity
     */
    public function _get($name)
    {
        $this->_inAllowedFields($name);

        $accessor = 'get' . ucfirst($name);
        if (method_exists($this, $accessor) && is_callable(array($this, $accessor))) {
            return $this->$accessor($name);
        }

        if (!isset($this->_values[$name])) {
            throw new \InvalidArgumentException("$name has not been set for this entity yet");
        }

        $field = $this->_values[$name];
        if ($field instanceof AbstractProxy) {
            $field = $field->load();
        }

        return $field;
    }

    /**
     * Is field's value set
     *
     * @param $name
     * @return bool
     */
    public function _isset($name)
    {
        $this->_inAllowedFields($name);

        return isset($this->_values[$name]);
    }

    /**
     * Unset field
     *
     * @param $name
     * @return bool
     */
    public function _unset($name)
    {
        $this->_inAllowedFields($name);

        if (isset($this->_values[$name])) {
            unset($this->_values[$name]);

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
        if (!in_array($name, $this->_allowedFields)) {
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
        return $this->_values;
    }

    /**
     * Return entry id value
     *
     * @return mixed|AbstractEntity
     */
    public function getId()
    {
        return $this->_get(self::$_primaryKey);
    }

    /**
     * Return entry primary key name
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return self::$_primaryKey;
    }
}