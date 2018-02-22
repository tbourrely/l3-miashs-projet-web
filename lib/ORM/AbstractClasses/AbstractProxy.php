<?php
/**
 * File "AbstractProxy.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\AbstractClasses;
use Pure\ORM\Interfaces\ProxyInterface;

/**
 * Class AbstractProxy
 *
 * @package Pure\ORM\AbstractClasses
 */
abstract class AbstractProxy implements ProxyInterface
{
    /**
     * @var AbstractMapper
     */
    protected $_mapper;

    /**
     * Params
     *
     * @var
     */
    protected $_params;

    /**
     * AbstractProxy constructor.
     *
     * @param AbstractMapper $mapper
     * @param array $params : 'field' => 'value'
     */
    public function __construct(AbstractMapper $mapper, array $params)
    {
        $this->_mapper = $mapper;
        $this->_params = $params;
    }

    /**
     * Getter for the mapper
     *
     * @return mixed
     */
    public function getMapper()
    {
        return $this->_mapper;
    }

    /**
     * Getter for the params (id)
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Build the 'criteria' string
     *
     * @return string
     */
    public function buildCriteria()
    {
        $criteria = '';
        $nbParams = count($this->getParams());

        if ($nbParams > 0) {

            $keys = array_keys($this->getParams());
            $values = array_values($this->getParams());

            $criteria .= $keys[0] . "='" . $values[0] . "'";

            if ($nbParams > 1) {
                for ($i = 1; $i < $nbParams; $i++) {
                    $criteria .= ' AND ' . $keys[$i] . "='" . $values[$i] . "'";
                }
            }
        }

        return $criteria;
    }

    abstract public function load();
}