<?php
/**
 * File "MysqlAdapter.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\Classes;
use Pure\ORM\AbstractClasses\AbstractModel;
use Pure\ORM\Exceptions\MysqlAdapterException;
use Pure\ORM\Interfaces\DatabaseAdapterInterface;

/**
 * Class MysqlAdapter
 *
 * @package Pure\ORM\Classes
 */
class MysqlAdapter implements DatabaseAdapterInterface
{
    /**
     * Mysqli instance
     *
     * @var \mysqli|null
     */
    protected $_link;

    /**
     * Mysqli_result instance
     *
     * @var \mysqli_result|null
     */
    protected $_result;

    /**
     * MysqlAdapter constructor.
     */
    public function __construct()
    {
        // nothing to do here for now
    }

    /**
     * Disconnect DB on class destruction
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Connect to DB
     *
     * @param array $config
     * @return bool|\mysqli|null
     * @throws MysqlAdapterException
     */
    public function connect(array $config)
    {
        if ( !isset($this->_link) ) {

            if ( !empty($config) ) {

                $this->_link = new \mysqli(
                    $config['host'],
                    $config['user'],
                    $config['password'],
                    $config['dbname']
                );

                if ($this->_link->connect_error) {
                    throw new MysqlAdapterException('Connection to db impossible');
                }

            }

        }

        return false;
    }

    /**
     * Disconnect DB
     *
     * @return bool
     */
    public function disconnect()
    {
        if ( isset($this->_link) ) {
            mysqli_close($this->_link);
            $this->_link = null;

            return true;
        }

        return false;
    }

    /**
     * Assign this adapter to the models
     */
    public function bootModel()
    {
        AbstractModel::setAdapter($this);
    }

    /**
     * Escape string
     *
     * @param $value
     * @return string
     */
    public function quoteValue($value)
    {
        if ( $value === null ) {
            $value = 'NULL';
        } elseif (!is_numeric($value)) {
            $value = "'" . mysqli_real_escape_string($this->_link, $value) . "'";
        }

        return $value;
    }

    /**
     * Update table
     *
     * @param $table
     * @param array $data
     * @param string $where
     * @return int
     */
    public function update($table, array $data, $where = "")
    {
        $set = array();

        foreach ($data as $field => $value) {
            $set[] = $field . '=' . $this->quoteValue($value);
        }

        $query = "UPDATE $table SET " . implode(',', $set) . ((!empty($where)) ? ' WHERE ' . $where : '') . ";";

        $this->query($query);

        return $this->affectedRows();
    }

    /**
     * Insert in table
     *
     * @param $table
     * @param array $data
     * @return mixed|null
     */
    public function insert($table, array $data)
    {
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map( array($this, 'quoteValue'), array_values($data) ));

        $query = "INSERT INTO $table (" . $fields . ") VALUES (" . $values . ");";

        $this->query($query);

        return $this->getInsertId();
    }

    /**
     * Delete in table
     *
     * @param $table
     * @param string $where
     * @return int
     */
    public function delete($table, $where = "")
    {
        $query = "DELETE FROM $table" . (isset($where)? ' WHERE ' . $where : '') . ';';

        $this->query($query);

        return $this->affectedRows();
    }

    /**
     * Select from table
     *
     * @param $table
     * @param string $where
     * @param string $fields
     * @param string $order
     * @param null $limit
     * @param null $offset
     * @return int
     */
    public function select($table, $where = "", $fields = "*", $order = "", $limit = null, $offset = null)
    {
        $query = "SELECT $fields FROM $table" .
            (!empty($where) ? ' WHERE ' . $where : '') .
            (isset($limit) ? ' LIMIT ' . $limit : '') .
            ((isset($offset) && isset($limit)) ? ' OFFSET ' . $offset : '') .
            (!empty($order) ? ' ODER BY ' . $order : '');

        $this->query($query);

        return $this->countRows();
    }

    /**
     * Execute query
     *
     * @param $query
     * @return bool|\mysqli_result|null
     * @throws MysqlAdapterException
     */
    public function query($query)
    {
        if (empty($query) || !is_string($query)) {
            throw new \InvalidArgumentException('The specified query is not valid');
        }

        if ( !($this->_result = $this->_link->query($query)) ) {
            throw new MysqlAdapterException('Error executing the specified query');
        }

        return $this->_result;
    }

    /**
     * Fetch row from current result
     *
     * @return bool|mixed
     */
    public function fetch()
    {
        if (isset($this->_result)) {
            if ( ($row = $this->_result->fetch_assoc()) === NULL ) {
                $this->freeResult();
            }

            return $row;
        }

        return false;
    }

    /**
     * Free current result
     *
     * @return bool
     */
    public function freeResult()
    {
        if (isset($this->_result)) {
            $this->_result->free();
            return true;
        }

        return false;
    }

    /**
     * Return last inserted id
     *
     * @return mixed|null
     */
    public function getInsertId()
    {
        return ($this->_link !== null) ? $this->_link->insert_id : null;
    }

    /**
     * return result's nb rows
     *
     * @return int
     */
    public function countRows()
    {
        return ($this->_result !== null) ? $this->_result->num_rows : 0;
    }

    /**
     * Return number of changed rows
     *
     * @return int
     */
    public function affectedRows()
    {
        return ($this->_link !== null) ? $this->_link->affected_rows : 0;
    }
}