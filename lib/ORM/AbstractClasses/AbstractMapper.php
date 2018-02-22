<?php
/**
 * File "AbstractMapper.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\AbstractClasses;
use Pure\ORM\Interfaces\MapperInterface;
use Pure\ORM\Interfaces\DatabaseAdapterInterface;
use Pure\ORM\Classes\EntityCollection;

/**
 * Class AbstractMapper
 *
 * @package Pure\ORM\AbstractClasses
 */
abstract class AbstractMapper implements MapperInterface
{
    /**
     * @var DatabaseAdapterInterface
     */
    protected $_adapter;

    /**
     * @var string
     */
    protected $_entityTable;

    /**
     * @var AbstractEntity
     */
    protected $_entityClass;

    /**
     * AbstractMapper constructor.
     *
     * @param DatabaseAdapterInterface $adapter
     * @param array $entityOptions
     */
    public function __construct(DatabaseAdapterInterface $adapter, array $entityOptions = array())
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

    /**
     * Check entity options
     */
    protected function _checkEntityOptions()
    {
        if (!isset($this->_entityTable)) {
            throw new \RuntimeException('Entity table hasn\'t been set');
        }

        if (!isset($this->_entityClass)) {
            throw new \RuntimeException('Entity class hasn\'t been set');
        }
    }

    /**
     * Setter
     *
     * @param $table
     * @return $this
     */
    public function setEntityTable($table)
    {
        if (!is_string($table) || empty($table)) {
            throw new \InvalidArgumentException('Invalid entity table');
        }

        $this->_entityTable = $table;

        return $this;
    }

    /**
     * Setter
     *
     * @param $class
     * @return $this
     */
    public function setEntityClass($class)
    {
        if (!is_subclass_of($class, 'AbstractEntity')) {
            throw new \InvalidArgumentException('Invalid entity class');
        }

        $this->_entityClass = $class;

        return $this;
    }

    /**
     * Getter
     *
     * @return DatabaseAdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getEntityTable()
    {
        return $this->_entityTable;
    }

    /**
     * Getter
     *
     * @return AbstractEntity
     */
    public function getEntityClass()
    {
        return $this->_entityClass;
    }

    /**
     *  Find entity by id
     *
     * @param $id
     * @return null
     */
    public function findById($id)
    {
        $entityClass = $this->_entityClass;
        $pk = $entityClass::$_primaryKey;

        $this->_adapter->select($this->_entityTable, "$pk = '$id'");

        if ($data = $this->_adapter->fetch()) {
            return $this->_createEntity($data);
        }

        return null;
    }

    /**
     * Find entities matching criteria
     *
     * @param string $criteria
     * @return EntityCollection
     */
    public function find($criteria = "")
    {
        $collection = new EntityCollection();

        $this->_adapter->select($this->_entityTable, $criteria);

        while ($data = $this->_adapter->fetch()) {
            $collection->add(null, $this->_createEntity($data));
        }

        return $collection;
    }

    /**
     * Insert entity
     *
     * @param AbstractEntity $entity
     * @return mixed
     */
    public function insert(AbstractEntity $entity)
    {
        $this->_checkEntity($entity);

        return $this->_adapter->insert($this->_entityTable, $entity->toArray());
    }

    /**
     * Update entity
     *
     * @param AbstractEntity $entity
     * @return mixed
     */
    public function update(AbstractEntity $entity)
    {
        $this->_checkEntity($entity);

        $id = $entity->getId();
        $idName = $entity->getPrimaryKey();
        $data = $entity->toArray();
        unset($data[$idName]);

        return $this->_adapter->update($this->_entityTable, $data, "$idName = $id");
    }

    /**
     * Delete entities matching criteria
     *
     * @param AbstractEntity $entity
     * @param string $criteria
     * @return mixed
     */
    public function delete(AbstractEntity $entity, $criteria = "")
    {
        $this->_checkEntity($entity);

        return $this->_adapter->delete($this->_entityTable, $criteria);
    }

    /**
     * check $this->_entityClass type
     *
     * @param AbstractEntity $entity
     */
    protected function _checkEntity(AbstractEntity $entity)
    {
        if (!$entity instanceof $this->_entityClass) {
            throw new \InvalidArgumentException("Entity passed is not an instance of $this->_entityTable");
        }
    }

    /**
     * Mapper setter, used for relationships (one-to-many, one-to-one, ...)
     *
     * @param $mapperName
     * @param $mapper
     */
    public function setMapper($mapperName, $mapper)
    {
        $this->$mapperName = $mapper;
    }

    /**
     * Getter for a mapper
     *
     * @param $mapperName
     * @return mixed
     */
    public function getMapper($mapperName)
    {
        return $this->$mapperName;
    }

    /**
     * Create entity
     *
     * @param array $data
     * @return mixed
     */
    abstract protected function _createEntity(array $data);
}