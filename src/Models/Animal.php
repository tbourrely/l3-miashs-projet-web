<?php
/**
 * File "Animal.php"
 * @author Thomas Bourrely
 * 28/03/2018
 */

namespace App\Models;

use Pure\ORM\AbstractClasses\AbstractModel;

/**
 * Class Animal
 * @package App\Models
 */
class Animal extends AbstractModel
{
    /**
     * @var string $table, table dans la BDD
     */
    protected static $table = 'Animal';

    /**
     * @var string $primaryKey, clé primaire de la table
     */
    protected static $primaryKey = 'idAnimal';

    /**
     * @var array $allowedFields, Champs modifiables dans la ta table
     */
    protected $allowedFields = array('idAnimal', 'nom', 'age', 'type', 'race', 'ville', 'photo', 'description', 'idCompte');

    /**
     * @var string $pivotTable, table pivot permettant de lier les Animaux avec les Comptes
     */
    public $pivotTable = 'MatchAC';

    /**
     * Retourne l'animal si il existe
     *
     * @param $id
     * @return bool|mixed|null
     */
    public static function exists($id)
    {
        $id = static::$_adapter->quoteValue($id);

        $animal = static::where("idAnimal = $id");

        if (count($animal) === 1) {
            return $animal->first();
        }

        return false;
    }

    /**
     * Créé une relation entre l'animal courant et un Compte (enregistre le match)
     *
     * @param $idUser
     * @return bool
     */
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

    /**
     * Vérifie si l'animal courant a déjà matché avec $idUser
     *
     * @param $idUser
     * @return bool
     */
    public function isMatched($idUser)
    {
        $idAnimal = static::$_adapter->quoteValue($this->idAnimal);
        $idCompte = static::$_adapter->quoteValue($idUser);

        $query = "idAnimal = $idAnimal AND idCompte = $idCompte";
        $alreadyExists = static::$_adapter->select($this->pivotTable, $query);

        return $alreadyExists === 0 ? false : true;
    }

    /**
     * Récupère le dernier animal ajouté sucpetible d'être matché
     *
     * @param $idUser
     * @return bool|mixed
     */
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

    /**
     * Cherche l'animal précédent pouvant être matché
     *
     * @param $idAnimal
     * @param $idUser
     * @param $maxPk
     * @return bool|mixed
     */
    public static function findPrevious($idAnimal, $idUser, $maxPk)
    {
        $idUser = static::$_adapter->quoteValue($idUser);
        $idAnimal = static::$_adapter->quoteValue($idAnimal);

        if ($idAnimal > $maxPk) {
            $res = false;
        } else {
            $animal = Animal::where("idCompte != $idUser AND idAnimal = $idAnimal");

            if ($animal->count() === 0 || $animal->first()->isMatched($idUser)) {
                $res = static::findPrevious($idAnimal + 1, $idUser, $maxPk);
            } else {
                $res = $idAnimal;
            }
        }

        return $res;
    }

    /**
     * Cherche l'animal suivant pouvant être matché
     *
     * @param $idAnimal
     * @param $idUser
     * @param $minPk
     * @return bool|mixed
     */
    public static function findNext($idAnimal, $idUser, $minPk)
    {
        $idUser = static::$_adapter->quoteValue($idUser);
        $idAnimal = static::$_adapter->quoteValue($idAnimal);

        if ($idAnimal < $minPk) {
            $res = false;
        } else {
            $animal = Animal::where("idCompte != $idUser AND idAnimal = $idAnimal");

            if ($animal->count() === 0 || $animal->first()->isMatched($idUser)) {
                $res = static::findNext($idAnimal - 1, $idUser, $minPk);
            } else {
                $res = $idAnimal;
            }
        }

        return $res;
    }

    /**
     * Retourne les Comptes ayant matché avec cet animal
     *
     * @return null|\Pure\ORM\Classes\EntityCollection
     */
    public function matchedAccounts()
    {
        return $this->belongsToMany('App\Models\Compte', 'MatchAC', 'idAnimal', 'idCompte');
    }

    /**
     * Retourne le Compte propriétaire de l'animal
     *
     * @return mixed|null
     */
    public function getCompte()
    {
        return $this->belongsTo('App\Models\Compte', 'idCompte', 'idCompte');
    }
}