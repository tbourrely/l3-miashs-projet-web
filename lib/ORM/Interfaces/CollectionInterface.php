<?php
/**
 * File "CollectionInterface.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\Interfaces;
use Pure\ORM\AbstractClasses\AbstractModel;

/**
 * Interface CollectionInterface
 *
 * @package Pure\ORM\Interfaces
 */
interface CollectionInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * Return all the elements of the collection as an array
     *
     * @return array
     */
    public function all();

    /**
     * Return the first element of the collection
     *
     * @return mixed
     */
    public function first();

    /**
     * Clear entries
     *
     * @return mixed
     */
    public function clear();

    /**
     * Reset entries array
     *
     * @return mixed
     */
    public function reset();

    /**
     * Add an entry
     *
     * @param $key
     * @param AbstractModel $entity
     * @return mixed
     */
    public function add($key, AbstractModel $entity);

    /**
     * Return an entry
     *
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * Remove an entry
     *
     * @param $key
     * @return mixed
     */
    public function remove($key);

    /**
     * Test if entry exists in collection
     *
     * @param $key
     * @return mixed
     */
    public function exists($key);
}