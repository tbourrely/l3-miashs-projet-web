<?php
/**
 * File "Adresse.php"
 * @author Thomas Bourrely
 * 27/02/2018
 */

namespace App\Model;

use Pure\ORM\AbstractClasses\AbstractModel;

class Adresse extends AbstractModel
{
    protected static $table = 'Adresse';
    protected $primaryKey = 'id';
    protected $allowedFields = array('id', 'adresse', 'cp', 'ville', 'idClient');

    public function getClient()
    {
        $client = $this->belongsTo('App\Model\Client', 'id', 'idClient');

        if (isset($client)) {
            return $client->first();
        }
    }
}