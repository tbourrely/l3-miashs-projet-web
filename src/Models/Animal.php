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
    protected $allowedFields = array('idAnimal', 'nom', 'age', 'type', 'race', 'ville', 'photo', 'description', 'idCompte');

    public $pivotTable = 'MatchAC';

    public static function exists($id)
    {
        $id = static::$_adapter->quoteValue($id);

        $animal = static::where("idAnimal = $id");

        if (count($animal) === 1) {
            return $animal->first();
        }

        return false;
    }

    public function linkWith($idUser)
    {
        $idAnimal = static::$_adapter->quoteValue($this->idAnimal);
        $idCompte = static::$_adapter->quoteValue($idUser);

        $query = "idAnimal = $idAnimal AND idCompte = $idCompte";
        $alreadyExists = static::$_adapter->select($this->pivotTable, $query);

        if ($alreadyExists === 0) {
            // pas de match correspondant, on peut donc l'ajouter

            $data = [
                'idAnimal'  => $idAnimal,
                'idCompte'  => $idCompte
            ];

            static::$_adapter->insert($this->pivotTable, $data);

            if (($alreadyExists = static::$_adapter->select($this->pivotTable, $query)) === 1) {
                return true;
            }
        }

        return false;
    }

    public function isMatched($idUser)
    {
        $idAnimal = static::$_adapter->quoteValue($this->idAnimal);
        $idCompte = static::$_adapter->quoteValue($idUser);

        $query = "idAnimal = $idAnimal AND idCompte = $idCompte";
        $alreadyExists = static::$_adapter->select($this->pivotTable, $query);

        return $alreadyExists === 0 ? false : true;
    }

    public static function findLatest($idUser)
    {
        $idCompte = static::$_adapter->quoteValue($idUser);
        $table = static::$table;

        $animal_list = [];

        $animals = static::where("idCompte != $idUser");

        foreach ($animals->getIterator() as $animal) {
            /** @var $animal Animal */
            if ($animal->isMatched($idUser) === false) {
                // pas matché

                $animal_list[] = $animal->idAnimal;
            }
        }

        if (count($animal_list) > 0) {
            return array_pop($animal_list);
        }

        return false;
    }
}