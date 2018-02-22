<?php

/**
 * File "ClientMapper.php"
 * @author Thomas Bourrely
 * 13/02/2018
 */
namespace App\Mapper;

use App\Entity\ClientEntity;
use Pure\ORM\Classes\EntityProxy;
use Pure\ORM\Interfaces\DatabaseAdapterInterface;

class ProduitMapper extends \Pure\ORM\AbstractClasses\AbstractMapper
{
    protected $_entityTable = 'Produit';
    protected $_entityClass = '\App\Entity\ProduitEntity';
    protected $_clientMapper;

    protected function _createEntity(array $data)
    {
        $produit = new $this->_entityClass(array(
            'id' => $data['id'],
            'nom' => $data['nom'],
            'prix' => $data['prix'],
//            'client' => new EntityProxy($this->_clientMapper, ['id' => $data['idClient']])
        ));

        return $produit;
    }
}