<?php
/**
 * File "ProxyEntityProxy.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM;


class EntityProxy extends AbstractProxy implements ProxyInterface
{
    protected $_entity;

    public function load()
    {
        if (!isset($this->_entity)) {
            $this->_entity = $this->_mapper->findById($this->_params);

            if (!$this->_entity instanceof AbstractEntity) {
                throw new \RuntimeException('Unable to load the related entity');
            }
        }

        return $this->_entity;
    }
}