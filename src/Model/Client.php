<?php

/**
 * File "Client.php"
 * @author Thomas Bourrely
 * 22/02/2018
 */
namespace App\Model;

use Pure\ORM\AbstractClasses\AbstractModel;

class Client extends AbstractModel
{
    protected static $table = 'Client';
    protected static $primaryKey = 'id';
    protected $allowedFields = array('id', 'nom', 'prenom');

    public function getAdress()
    {
        return $this->hasOne('App\Model\Adresse', 'idClient');
    }

    public function getRoles()
    {
        return $this->belongsToMany('App\Model\Role', 'ClientRole', 'idClient', 'idRole');
    }
}