<?php

/**
 * File "Produit.php"
 * @author Thomas Bourrely
 * 22/02/2018
 */
namespace App\Model;

use Pure\ORM\AbstractClasses\AbstractModel;

class Produit extends AbstractModel
{
    protected static $table = 'Produit';
    protected $primaryKey = 'id';
    protected $allowedFields = array('id', 'nom', 'prix', 'idClient', 'idCategorie');
}