<?php
/**
 * File "Adresse.php"
 * @author Thomas Bourrely
 * 27/02/2018
 */

namespace App\Model;

use Pure\ORM\AbstractClasses\AbstractModel;

class Adresse extends AbstractModel
{
    protected static $table = 'Adresse';
    protected $primaryKey = 'id';
    protected $allowedFields = array('id', 'adresse', 'cp', 'ville', 'idClient');
}