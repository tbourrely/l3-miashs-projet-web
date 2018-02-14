<?php
/**
 * File "AbstractProxy.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\AbstractClasses;

/**
 * Class AbstractProxy
 *
 * @package Pure\ORM\AbstractClasses
 */
abstract class AbstractProxy
{
    /**
     * @var AbstractMapper
     */
    protected $_mapper;

    /**
     * AbstractProxy constructor.
     *
     * @param AbstractMapper $mapper
     */
    public function __construct(AbstractMapper $mapper)
    {
        $this->_mapper = $mapper;
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
}