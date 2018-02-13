<?php
/**
 * File "AbstractEntity.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM;


abstract class AbstractEntity
{
    protected $_primaryKey;
    protected $_values = array();
    protected $_allowedFields = array();

    public function __construct(array $fields)
    {
        foreach ($fields as $name => $value) {
            $this->$name = $value;
        }
    }

    public function _set($name, $value)
    {
        $this->_inAllowedFields($name);

        $mutator = 'set' . ucfirst($name);
        if (method_exists($this, $mutator) && is_callable(array($this, $mutator))) {
            $this->$mutator($value);
        } else {
            $this->_values[$name] = $value;
        }
    }

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
        if ($field instanceof EntityProxy) {
            $field = $field->load();
        }

        return $field;
    }

    public function _isset($name)
    {
        $this->_inAllowedFields($name);

        return isset($this->_values[$name]);
    }

    public function _unset($name)
    {
        $this->_inAllowedFields($name);

        if (isset($this->_values[$name])) {
            unset($this->_values[$name]);

            return true;
        }

        return false;
    }


    protected function _inAllowedFields($name)
    {
        if (!in_array($name, $this->_allowedFields)) {
            throw new \InvalidArgumentException("$name not in the allowed fields");
        }
    }

    public function toArray()
    {
        return $this->_values;
    }

    public function getId()
    {
        return $this->_get($this->_primaryKey);
    }

    public function getIdFieldName()
    {
        return $this->_primaryKey;
    }
}