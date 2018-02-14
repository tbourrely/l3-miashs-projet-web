<?php
/**
 * File "ProxyInterface.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */

namespace Pure\ORM\Interfaces;

/**
 * Interface ProxyInterface
 *
 * @package Pure\ORM\Interfaces
 */
interface ProxyInterface
{
    /**
     * Load collection
     *
     * @return mixed
     */
    public function load();
}