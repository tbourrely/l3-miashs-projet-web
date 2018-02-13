<?php
/**
 * File "CollectionProxy.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM;


use Traversable;

class CollectionProxy extends AbstractProxy implements ProxyInterface, \Countable, \IteratorAggregate
{
    protected $_collection;

    public function getIterator()
    {
        return $this->load();
    }

    public function load()
    {
        if (!isset($this->_collection)) {
            $this->_collection = $this->_mapper->find($this->_params);

            if (!$this->_collection instanceof EntityCollection) {
                throw new \RuntimeException('Unable to load the related collection');
            }
        }

        return $this->_collection;
    }

    public function count()
    {
        return count($this->_collection);
    }
}