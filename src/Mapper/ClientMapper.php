<?php

/**
 * File "ClientMapper.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */
class ClientMapper extends \Pure\ORM\AbstractMapper
{
    protected $_entityTable = 'Client';
    protected $_entityClass = 'ClientEntity';

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