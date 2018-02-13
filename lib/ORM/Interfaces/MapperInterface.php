<?php
/**
 * File "MapperInterface.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM;


interface MapperInterface
{
    public function findById($id);

    public function find($criteria = "");

    public function insert(AbstractEntity $entity);

    public function update(AbstractEntity $entity);

    public function delete(AbstractEntity $entity, $criteria = "");
}