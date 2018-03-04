<?php

/**
 * File "Role.php"
 * @author Thomas Bourrely
 * 22/02/2018
 */
namespace App\Model;

use Pure\ORM\AbstractClasses\AbstractModel;

class Role extends AbstractModel
{
    protected static $table = 'Role';
    protected static $primaryKey = 'id';
    protected $allowedFields = array('id', 'type');

    public function getClients()
    {
        return $this->belongsToMany('App\Model\Client', 'ClientRole', 'idRole', 'idClient');
    }
}