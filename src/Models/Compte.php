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

    public static function userExists($login, $password)
    {
        $login = static::$_adapter->quoteValue($login);
        $password = static::$_adapter->quoteValue($password);

        $users = static::where("login = $login AND password = $password");

        return (count($users) === 1);
    }
}