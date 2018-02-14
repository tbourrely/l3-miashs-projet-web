<?php
/**
 * File "MapperInterface.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\Interfaces;
use Pure\ORM\AbstractClasses\AbstractEntity;

/**
 * Interface MapperInterface
 *
 * @package Pure\ORM\Interfaces
 */
interface MapperInterface
{
    /**
     * Find entity by id
     *
     * @param $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Find entities matching specified criteria
     *
     * @param string $criteria
     * @return mixed
     */
    public function find($criteria = "");

    /**
     * Insert entity
     *
     * @param AbstractEntity $entity
     * @return mixed
     */
    public function insert(AbstractEntity $entity);

    /**
     * Update entity
     *
     * @param AbstractEntity $entity
     * @return mixed
     */
    public function update(AbstractEntity $entity);

    /**
     * Delete entities matching specified criteria
     *
     * @param AbstractEntity $entity
     * @param string $criteria
     * @return mixed
     */
    public function delete(AbstractEntity $entity, $criteria = "");
}