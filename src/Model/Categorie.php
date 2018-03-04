<?php

/**
 * File "Categorie.php"
 * @author Thomas Bourrely
 * 22/02/2018
 */
namespace App\Model;

use Pure\ORM\AbstractClasses\AbstractModel;

class Categorie extends AbstractModel
{
    protected static $table = 'Categorie';
    protected static $primaryKey = 'id';
    protected $allowedFields = array('id', 'nom');

    public function getProduits()
    {
        return $this->hasMany('App\Model\Produit', 'idCategorie');
    }
}