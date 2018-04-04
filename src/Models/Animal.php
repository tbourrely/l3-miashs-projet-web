<?php
/**
 * File "Animal.php"
 * @author Thomas Bourrely
 * 28/03/2018
 */

namespace App\Models;

use Pure\ORM\AbstractClasses\AbstractModel;

class Animal extends AbstractModel
{
    protected static $table = 'Animal';
    protected static $primaryKey = 'idAnimal';
    protected $allowedFields = array('idAnimal', 'nom', 'age', 'type', 'race', 'ville', 'photo', 'idCompte');

    public static function exists($id)
    {
        $id = static::$_adapter->quoteValue($id);

        $animal = static::where("idAnimal = $id");

        if (count($animal) === 1) {
            return $animal->first();
        }

        return false;
    }
}