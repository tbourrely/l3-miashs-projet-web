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
        $this->render('homepage', ['title' => 'Pure homepage']);
    }
}