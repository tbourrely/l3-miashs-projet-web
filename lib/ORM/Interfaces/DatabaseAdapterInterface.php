<?php
/**
 * File "DatabaseAdapter.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\Interfaces;

/**
 * Interface DatabaseAdapterInterface
 *
 * @package Pure\ORM\Interfaces
 */
interface DatabaseAdapterInterface
{
    /**
     * Connect to DB
     *
     * @return mixed
     */
    public function connect();

    /**
     * Disconnect from DB
     *
     * @return mixed
     */
    public function disconnect();

    /**
     * Perform an update query
     *
     * @param $table
     * @param array $data
     * @param $conditions
     * @return mixed
     */
    public function update($table, array $data, $conditions);

    /**
     * Perform an insert query
     *
     * @param $table
     * @param array $data
     * @return mixed
     */
    public function insert($table, array $data);

    /**
     * Perform a delete query
     *
     * @param $table
     * @param $conditions
     * @return mixed
     */
    public function delete($table, $conditions);

    /**
     * Perform a query
     *
     * @param $query
     * @return mixed
     */
    public function query($query);

    /**
     * Fetch result rows
     *
     * @return mixed
     */
    public function fetch();

    /**
     * Perform a select query
     *
     * @param $table
     * @param string $conditions
     * @param string $fields
     * @param string $order
     * @param null $limit
     * @param null $offset
     * @return mixed
     */
    public function select($table, $conditions = "", $fields = "*", $order = "", $limit = null, $offset = null);

    /**
     * Retur the latest inserted id
     *
     * @return mixed
     */
    public function getInsertId();

    /**
     * Count result rows
     *
     * @return mixed
     */
    public function countRows();

    /**
     * Count affected rows
     *
     * @return mixed
     */
    public function affectedRows();

}