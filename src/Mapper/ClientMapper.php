<?php

/**
 * File "ClientMapper.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */
namespace App\Mapper;

use App\Entity\ClientEntity;

class ClientMapper extends \Pure\ORM\AbstractClasses\AbstractMapper
{
    protected $_entityTable = 'Client';
    protected $_entityClass = '\App\Entity\ClientEntity';

    protected function _createEntity(array $data)
    {
        $client = new ClientEntity(array(
            'id'        => $data['id'],
            'nom'       => $data['nom'],
            'prenom'    => $data['prenom']
        ));

        return $client;
    }
}