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
     */
    public function __construct(AbstractMapper $mapper, $params)
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

    abstract public function load();
}