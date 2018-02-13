<?php
/**
 * File "AbstractMapper.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM;


abstract class AbstractMapper implements MapperInterface
{
    protected $_adapter;

    protected $_entityTable;

    protected $_entityClass;

    public function __construct(DatabaseAdapter $adapter, array $entityOptions = array())
    {
        $this->_adapter = $adapter;

        if ( isset($entityOptions['entityTable']) ) {
            $this->setEntityTable($entityOptions['entityTable']);
        }

        if ( isset($entityOptions['entityClass']) ) {
            $this->setEntityClass($entityOptions['entityClass']);
        }

        $this->_checkEntityOptions();
    }

    protected function _checkEntityOptions()
    {
        if (!isset($this->_entityTable)) {
            throw new \RuntimeException('Entity table hasn\'t been set');
        }

        if (!isset($this->_entityClass)) {
            throw new \RuntimeException('Entity class hasn\'t been set');
        }
    }

    public function setEntityTable($table)
    {
        if (!is_string($table) || empty($table)) {
            throw new \InvalidArgumentException('Invalid entity table');
        }

        $this->_entityTable = $table;

        return $this;
    }

    public function setEntityClass($class)
    {
        if (!is_subclass_of($class, 'AbstractEntity')) {
            throw new \InvalidArgumentException('Invalid entity class');
        }

        $this->_entityClass = $class;

        return $this;
    }

    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function getEntityTable()
    {
        return $this->_entityTable;
    }

    public function getEntityClass()
    {
        return $this->_entityClass;
    }

    public function findById($id)
    {
        $this->_adapter->select($this->_entityTable, "id = '$id'");

        if ($data = $this->_adapter->fetch()) {
            return $this->_createEntity($data);
        }

        return null;
    }

    public function find($criteria = "")
    {
        $collection = array();

        $this->_adapter->select($this->_entityTable, $criteria);

        while ($data = $this->_adapter->fetch()) {
            $collection[] = $this->_createEntity($data);
        }

        return $collection;
    }

    public function insert(AbstractEntity $entity)
    {
        $this->_checkEntity($entity);

        return $this->_adapter->insert($this->_entityTable, $entity->toArray());
    }

    public function update(AbstractEntity $entity)
    {
        $this->_checkEntity($entity);

        $id = $entity->getId();
        $idName = $entity->getIdFieldName();
        $data = $entity->toArray();
        unset($data[$idName]);

        return $this->_adapter->update($this->_entityTable, $data, "$idName = $id");
    }

    public function delete(AbstractEntity $entity, $criteria = "")
    {
        $this->_checkEntity($entity);

        return $this->_adapter->delete($this->_entityTable, $criteria);
    }

    protected function _checkEntity(AbstractEntity $entity)
    {
        if (!$entity instanceof $this->_entityClass) {
            throw new \InvalidArgumentException("Entity passed is not an instance of $this->_entityTable");
        }
    }

    abstract protected function _createEntity(array $data);
}