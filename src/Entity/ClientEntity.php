<?php

/**
 * File "ClientEntity.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */
namespace App\Entity;

class ClientEntity extends \Pure\ORM\AbstractClasses\AbstractEntity
{
    public static $_primaryKey = 'id';
    protected $_allowedFields = array('id', 'nom', 'prenom');
}