<?php
/**
 * File "Compte.php"
 * @author Thomas Bourrely
 * 28/03/2018
 */

namespace App\Models;

use Pure\ORM\AbstractClasses\AbstractModel;

/**
 * Class Compte
 * @package App\Models
 */
class Compte extends AbstractModel
{
    /**
     * @var string $table, table dans la BDD
     */
    protected static $table = 'Compte';

    /**
     * @var string $primaryKey, clé primaire
     */
    protected static $primaryKey = 'idCompte';

    /**
     * @var array $allowedFields, champs modifiables
     */
    protected $allowedFields = array('idCompte', 'email', 'password', 'login');

    /**
     * @param $login
     * @return bool|mixed|null
     */
    public static function getByLogin($login)
    {
        $login = static::$_adapter->quoteValue($login);

        $users = static::where("login = $login");

        if ((count($users) === 1)) {
            return $users->first();
        }

        return false;
    }

    /**
     * @param $id
     * @return bool|mixed|null
     */
    public static function getById($id)
    {
        $id = static::$_adapter->quoteValue($id);

        $user = static::where("idCompte = $id");

        if ((count($user) === 1)) {
            return $user->first();
        }

        return false;
    }

    /**
     * @param $email
     * @return bool
     */
    public function updateEmail($email)
    {
        $emailS = static::$_adapter->quoteValue($email);

        $users = static::where("email = $emailS");

        if (count($users) === 0) {
            $this->email = $email;
            return static::update($this) > 0;
        }

        return false;
    }

    /**
     * Récupère les animaux appartenant au compte courant
     *
     * @return \Pure\ORM\Classes\EntityCollection
     */
    public function getAnimals()
    {
        return $this->hasMany('App\Models\Animal', 'idCompte', 'idCompte');
    }

    /**
     * Récupère les animaux matchés
     *
     * @return null|\Pure\ORM\Classes\EntityCollection
     */
    public function matchedAnimals()
    {
        return $this->belongsToMany('App\Models\Animal', 'MatchAC', 'idCompte', 'idAnimal');
    }
}