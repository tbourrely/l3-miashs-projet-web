<?php
/**
 * File "EntityCollection.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\Classes;
use Pure\ORM\AbstractClasses\AbstractModel;
use Pure\ORM\Interfaces\CollectionInterface;

class EntityCollection implements CollectionInterface
{
    protected $_entities = array();

    public function __construct(array $entities = array())
    {
        $this->_entities = $entities;
        $this->reset();
    }

    public function toArray()
    {
        return $this->_entities;
    }

    public function clear()
    {
        $this->_entities = array();
    }

    public function reset()
    {
        reset($this->_entities);
    }

    public function add($key, AbstractModel $entity)
    {
        return $this->offsetSet($key, $entity);
    }

    public function get($key)
    {
        return $this->offsetGet($key);
    }

    public function remove($key)
    {
        return $this->offsetUnset($key);
    }

    public function exists($key)
    {
        return $this->offsetExists($key);
    }

    public function count()
    {
        return count($this->_entities);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    public function offsetExists($offset)
    {
        return isset($this->_entities[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->_entities[$offset]) ? $this->_entities[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof AbstractModel) {
            throw new \InvalidArgumentException('Entity must be an instance of AbstractEntity');
        }

        if (!isset($offset)) {
            $this->_entities[] = $value;
        } else {
            $this->_entities[$offset] = $value;
        }

        return true;
    }

    public function offsetUnset($offset)
    {
        if ($offset instanceof AbstractModel) {
            $this->_entities = array_filter($this->_entities, function($v) use ($offset) {
                return $v !== $offset;
            });

            return true;
        }

        if (isset($this->_entities[$offset])) {
            unset($this->_entities[$offset]);

            return true;
        }

        return false;
    }
}