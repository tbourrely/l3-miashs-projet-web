<?php
/**
 * File "AbstractProxy.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM;


abstract class AbstractProxy
{
    protected $_mapper;
    protected $_params;

    public function __construct(AbstractMapper $mapper, $params)
    {
        if (empty($params)) {
            throw new \InvalidArgumentException('Mapper parameters cannot be empty');
        }

        $this->_mapper = $mapper;
        $this->_params = $params;
    }

    /**
     * @return mixed
     */
    public function getMapper()
    {
        return $this->_mapper;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->_params;
    }

}