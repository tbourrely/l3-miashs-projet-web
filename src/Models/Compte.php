<?php
/**
 * File "Compte.php"
 * @author Thomas Bourrely
 * 28/03/2018
 */

namespace App\Models;

use Pure\ORM\AbstractClasses\AbstractModel;

class Compte extends AbstractModel
{
    protected static $table = 'Compte';
    protected static $primaryKey = 'idCompte';
    protected $allowedFields = array('idCompte', 'email', 'password', 'login');

    public static function getByLogin($login)
    {
        $login = static::$_adapter->quoteValue($login);

        $users = static::where("login = $login");

        if ((count($users) === 1)) {
            return $users->first();
        }

        return false;
    }

    public static function getById($id)
    {
        $id = static::$_adapter->quoteValue($id);

        $user = static::where("idCompte = $id");

        if ((count($user) === 1)) {
            return $user->first();
        }

        return false;
    }

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

    public function getAnimals()
    {
        return $animals = $this->hasMany('App\Models\Animal', 'idCompte', 'idCompte');
    }
}