<?php

/**
 * File "ClientMapper.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */
namespace App\Mapper;

use App\Entity\ClientEntity;
use Pure\ORM\Classes\CollectionProxy;

class ClientMapper extends \Pure\ORM\AbstractClasses\AbstractMapper
{
    protected $_entityTable = 'Client';
    protected $_entityClass = '\App\Entity\ClientEntity';
    protected $_productMapper;

    protected function _createEntity(array $data)
    {
        $client = new ClientEntity(array(
            'id'        => $data['id'],
            'nom'       => $data['nom'],
            'prenom'    => $data['prenom'],
            'produits'  => new CollectionProxy($this->_productMapper, ['idClient' => $data['id']])
        ));

        return $client;
    }
}