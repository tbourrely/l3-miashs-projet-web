<?php
/**
 * File "ProfilController.php"
 * @author Thomas Bourrely
 * 19/03/2018
 */

namespace App\Controllers;

use App\Models\Compte;
use Pure\Controllers\Classes\BaseController;

class ProfilController extends BaseController
{
    public function index($id)
    {
        $this->render('profil', ['idProfil' => $id]);
    }


    public function loginGet()
    {
        $this->render('memberArea/login', ['action' => $this->getRouter()->url('loginPOST')]);
    }

    public function loginPost() {
        $errors = [];

        if (!isset($_POST['login'])) {
            $errors[] = 'Vous devez spécifier un login';
        }

        if (!isset($_POST['password'])) {
            $errors[] = 'Vous devez spécifier un mot de passe';
        }

        if (empty($errors)) {

            $result = Compte::userExists($_POST['login'], $_POST['password']);

            if ($result) {

                // logged in
                $_SESSION['logged_in'] = 1;

                if (isset($_SESSION['errors']['login'])) {
                    unset($_SESSION['errors']['login']);
                }

            } else {
                $errors[] = 'login ou mot de passe incorrect';

                $_SESSION['errors']['login'] = $errors;

                // redirige au formulaire de connexion si erreur
                $this->redirect($this->getRouter()->url('loginGET'));

            }

        }

    }
}