<?php

/**
 * File "ClientEntity.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */
class ClientEntity extends \Pure\ORM\AbstractEntity
{
    protected $_primaryKey = 'id';
    protected $_allowedFields = array('id', 'nom', 'prenom');
}