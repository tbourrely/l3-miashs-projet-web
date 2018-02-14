<?php
/**
 * File "ProxyEntityProxy.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\Classes;
use Pure\ORM\AbstractClasses\AbstractProxy;
use Pure\ORM\AbstractClasses\AbstractEntity;
use Pure\ORM\Interfaces\ProxyInterface;

/**
 * Class EntityProxy
 *
 * @package Pure\ORM\Classes
 */
class EntityProxy extends AbstractProxy implements ProxyInterface
{
    /**
     * @var AbstractEntity
     */
    protected $_entity;

    /**
     * Load an entity
     *
     * @return AbstractEntity
     */
    public function load()
    {
        if (!isset($this->_entity)) {
            $this->_entity = $this->_mapper->findById($this->_entity->getId());

            if (!$this->_entity instanceof AbstractEntity) {
                throw new \RuntimeException('Unable to load the related entity');
            }
        }

        return $this->_entity;
    }
}