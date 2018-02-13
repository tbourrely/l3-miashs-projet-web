<?php
/**
 * File "DatabaseAdapter.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM;


interface DatabaseAdapter
{
    public function connect();

    public function disconnect();

    public function update($table, array $data, $conditions);

    public function insert($table, array $data);

    public function delete($table, $conditions);

    public function query($query);

    public function fetch();

    public function select($table, $conditions = "", $fields = "*", $order = "", $limit = null, $offset = null);

    public function getInsertId();

    public function countRows();

    public function affectedRows();

}