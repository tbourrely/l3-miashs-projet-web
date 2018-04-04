<?php

/**
 * File "HomeController.php"
 * @author Thomas Bourrely
 * 13/03/2018
 */
namespace App\Controllers;

use Pure\Controllers\Classes\BaseController;

class HomeController extends BaseController
{
    public function home()
    {
        $profils = array();

        for ($i = 1; $i<22; $i++) {
            // $profils[$this->getRouter()->url('profilIndex', ['id' => $i])] = "/src/images/pic$i.jpg";
            $profils["/data/img/animaux/img$i.jpg"] = $this->getRouter()->url('loginGET');
        }

        $this->render('homepage', ['isHome' => true, 'profils' => $profils]);
    }
}