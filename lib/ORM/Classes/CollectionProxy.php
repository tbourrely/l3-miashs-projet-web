<?php
/**
 * File "CollectionProxy.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\Classes;
use Pure\ORM\AbstractClasses\AbstractProxy;


/**
 * Class CollectionProxy
 *
 * @package Pure\ORM\Classes
 */
class CollectionProxy extends AbstractProxy implements \Countable, \IteratorAggregate
{
    /**
     * @var EntityCollection
     */
    protected $_collection;

    /**
     * @return mixed|EntityCollection
     */
    public function getIterator()
    {
        return $this->load();
    }

    /**
     * Load EntityCollection
     *
     * @return EntityCollection
     */
    public function load()
    {
        if (!isset($this->_collection)) {

            $criteria = $this->buildCriteria();
            $this->_collection = $this->getMapper()->find($criteria);

            if (!$this->_collection instanceof EntityCollection) {
                throw new \RuntimeException('Unable to load the related collection');
            }
        }

        return $this->_collection;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_collection);
    }
}