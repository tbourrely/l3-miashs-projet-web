<?php
/**
 * File "ProfilController.php"
 * @author Thomas Bourrely
 * 19/03/2018
 */

namespace App\Controllers;

use Pure\Controllers\Classes\BaseController;

class ProfilController extends BaseController
{
    public function index($id)
    {
        $this->render('detail1', ['isHome' => false]);
    }
}