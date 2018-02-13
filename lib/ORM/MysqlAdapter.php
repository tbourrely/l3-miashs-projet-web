<?php
/**
 * File "MysqlAdapter.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM;

/**
 * Class MysqlAdapter
 *
 * @package Pure\ORM
 */
class MysqlAdapter implements DatabaseAdapter
{
    /**
     * Database config
     *
     * @var array
     */
    protected $_config;

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
     *
     * @param $dbIniFile
     * @throws MysqlAdapterException
     */
    public function __construct($dbIniFile)
    {
        $this->_link = null;
        $this->_result = null;

        if ( file_exists($dbIniFile) ) {
            $this->_config = parse_ini_file($dbIniFile);
        } else {
            throw new MysqlAdapterException('No such ini file');
        }
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
     * @return \mysqli|false
     * @throws MysqlAdapterException
     */
    public function connect()
    {
        if ( !isset($this->_link) ) {

            if ( !empty($this->_config) ) {

                $this->_link = mysqli_connect(
                    $this->_config['host'],
                    $this->_config['user'],
                    $this->_config['password'],
                    $this->_config['db']
                );

                if ( !mysqli_connect_error() ) {
                    return $this->_link;
                }

            }

            throw new MysqlAdapterException('Connection to db impossible');

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
     * Escape string
     *
     * @param $value
     * @return string
     */
    public function quoteValue($value)
    {
        $this->connect();

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

        $query = "UPDATE $table SET " . implode(',', $set) . ((!empty($where)) ? 'WHERE ' . $where : '') . ";";

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
        $query = "DELETE FROM $table" . (isset($where)? ' WHERE ' . $where : '');
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
            (isset($where) ? ' WHERE ' . $where : '') .
            (isset($limit) ? ' LIMIT ' . $limit : '') .
            (isset($offset) && isset($limit) ? ' OFFSET ' . $offset : '') .
            (isset($order) ? ' ODER BY ' . $order : '');

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

        $this->connect();

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
            if ( ($row = $this->_result->fetch_array()) === false ) {
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