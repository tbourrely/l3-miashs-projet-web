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
    protected $primaryKey = 'id';
    protected $allowedFields = array('id', 'nom', 'prenom');
}