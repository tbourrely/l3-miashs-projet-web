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

    /**
     * Formulaire GET
     */
    public function loginGet()
    {
        $this->render('memberArea/login', ['action' => $this->getRouter()->url('loginPOST')]);
    }

    /**
     * Formulaire POST
     */
    public function loginPost() {
        $errors = [];

        // test champs vide
        if (!isset($_POST['login'])) {
            $errors[] = 'Vous devez spécifier un login';
        }

        if (!isset($_POST['password'])) {
            $errors[] = 'Vous devez spécifier un mot de passe';
        }


        if (empty($errors)) {
            // champs remplis, on peut tester si l'utilisateur existe

            $result = Compte::userExists($_POST['login'], $_POST['password']);

            if ($result) {
                // utilisateur existe

                $_SESSION['logged_in'] = 1;

                // met en session quelques données utiles
                $_SESSION['user'] = array(
                    'idCompte'  => $result->idCompte,
                    'login'     => $result->login
                );

                if (isset($_SESSION['errors']['login'])) {
                    unset($_SESSION['errors']['login']);
                }

                // redirige sur la homepage
                $this->redirect($this->getRouter()->url('home'));
            } else {
                // utilisateur n'existe PAS

                $errors[] = 'login ou mot de passe incorrect';
                $_SESSION['errors']['login'] = $errors;

                // redirige vers le formulaire de connexion
                $this->redirect($this->getRouter()->url('loginGET'));
            }

        } // fin empty($errors)
    }
}