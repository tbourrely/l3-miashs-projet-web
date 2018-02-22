<?php
/**
 * File "EntityCollection.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\Classes;

use Pure\ORM\AbstractClasses\AbstractModel;
use Pure\ORM\Interfaces\CollectionInterface;

/**
 * Class EntityCollection
 *
 * @package Pure\ORM\Classes
 */
class EntityCollection implements CollectionInterface
{
    /**
     * @var array
     */
    protected $_entities = array();

    /**
     * EntityCollection constructor.
     *
     * @param array $entities
     */
    public function __construct(array $entities = array())
    {
        $this->_entities = $entities;
        $this->reset();
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->_entities;
    }

    /**
     * @return mixed|null
     */
    public function first()
    {
        if (empty($this->_entities)) {
            return null;
        }

        return $this->_entities[0];
    }

    /**
     * clear entries
     */
    public function clear()
    {
        $this->_entities = array();
    }

    /**
     * reset array cursor
     */
    public function reset()
    {
        reset($this->_entities);
    }

    /**
     * @param $key
     * @param AbstractModel $entity
     * @return bool
     */
    public function add($key, AbstractModel $entity)
    {
        return $this->offsetSet($key, $entity);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        return $this->offsetUnset($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_entities);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_entities[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->_entities[$offset]) ? $this->_entities[$offset] : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return bool
     */
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

    /**
     * @param mixed $offset
     * @return bool
     */
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