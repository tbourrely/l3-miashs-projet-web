<?php
/**
 * File "ProfilController.php"
 * @author Thomas Bourrely
 * 19/03/2018
 */

namespace App\Controllers;

use App\Models\Animal;
use App\Models\Compte;
use Pure\Controllers\Classes\BaseController;

class ProfilController extends BaseController
{
    public function index($id)
    {
        $this->render('profil', ['idProfil' => $id]);
    }

    /**
     * Formulaire connexion GET
     */
    public function loginGet()
    {
        $this->render('memberArea/login', ['action' => $this->getRouter()->url('loginPOST')]);
    }

    /**
     * Formulaire connexion POST
     */
    public function loginPost()
    {
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

            $result = Compte::getByLogin($_POST['login']);

            if ($result && password_verify($_POST['password'], $result->password)) {
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

            }

        } // fin empty($errors)

        // redirige vers le formulaire de connexion
        $this->redirect($this->getRouter()->url('loginGET'));
    }

    /**
     * Deconnexion
     */
    public function logout()
    {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user']);

        $this->redirect($this->getRouter()->url('home'));
    }

    /**
     * Formulaire modification profil GET
     */
    public function editGet()
    {
        $id_user = $_SESSION['user']['idCompte'];

        $user = Compte::getById($id_user);

        $params = [
            'action' => $this->getRouter()->url('editPOST'),
            'currentEmail' => (isset($user)) ? $user->email : ''
        ];

        $this->render('memberArea/edit', $params);
    }

    /**
     * Formulaire modification profil POST
     */
    public function editPost()
    {
        $errors = [];
        $success = [];
        $toUpdate = [];

        if (isset($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email non valide !';
        } else {
            $toUpdate['email'] = 1;
        }

        if ($_POST['password'] !== $_POST['password2']) {
            $errors[] = 'Les mots de passes doivent correspondrent !';
        } elseif(!empty($_POST['password'] && !empty($_POST['password2']))) {
            $toUpdate['password'] = 1;
        }


        $id_user = $_SESSION['user']['idCompte'];
        $user = Compte::getById($id_user);

        // email
        if (isset($toUpdate['email'])) {
            if ($user->email !== $_POST['email']) {
                if ($user->updateEmail($_POST['email'])) {
                    $success[] = 'Email mis à jour !';
                } else {
                    $errors[] = 'Impossible d\'utiliser cet email';
                }
            }
        }

        if (isset($toUpdate['password'])) {

            $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            if (Compte::update($user) > 0) {
                $success[] = 'Mot de passe mis à jour !';
            } else {
                $errors[] = 'Impossible de mettre à jour le mot de passe !';
            }

        }


        $_SESSION['errors']['edit'] = $errors;
        $_SESSION['success']['edit'] = $success;

        $this->redirect($this->getRouter()->url('editGET'));
    }

    /**
     * Page de gestion des animaux
     */
    public function editAnimauxGet()
    {
        $id_user = $_SESSION['user']['idCompte'];
        $user = Compte::getById($id_user);

        // pas de user
        if (!$user) {
            $this->redirect('home');
        }

        /**
         * @var $user Compte
         */
        $animals = $user->getAnimals();

        $this->render('memberArea/editAnimals', ['animals' => $animals]);
    }

    /**
     * Formulaire d'ajout d'un animal GET
     */
    public function addAnimauxGet()
    {
        $this->render('memberArea/addAnimals');
    }

    /**
     * Formulaire d'ajout d'un animal POST
     */
    public function addAnimauxPost()
    {
        $errors = [];
        $success = [];

        if (
            !isset(
                $_POST['nom'],
                $_POST['type'],
                $_POST['age'],
                $_POST['race'],
                $_POST['ville']
            )
        ) {
            $errors[] = "Veuillez de renseigner tous les champs !";
        }

        if (empty($errors)) {

            $fields = array(
                'nom' => $_POST['nom'],
                'age' => $_POST['age'],
                'type' => $_POST['type'],
                'race' => $_POST['race'],
                'ville' => $_POST['ville'],
                'photo' => '/data/img/animaux/default.png',
                'idCompte' => $_SESSION['user']['idCompte']
            );

            // upload image
            if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $destination = UPLOADS . DIRECTORY_SEPARATOR . $_FILES['photo']['name'];
                $tmp = $_FILES['photo']['tmp_name'];

                if (move_uploaded_file($tmp, $destination)) {
                    $fields['photo'] = UPLOADS_WEB . $_FILES['photo']['name'];
                }
            }

            // insert
            $animal = new Animal($fields);

            if (Animal::insert($animal)) {
                $success[] = 'Animal ajouté !';
            } else {
                $errors[] = "Impossible d'ajouter l'animal !";
            }

        }

        // redirect
        $_SESSION['errors']['addAnimal'] = $errors;
        $_SESSION['success']['addAnimal'] = $success;

        $this->redirect($this->getRouter()->url('addAnimauxGET'));
    }

    public function deleteAnimal($id)
    {
        $errors = [];
        $success = [];


        if (!is_numeric($id)) {
            $errors[] = 'id animal invalide';
        } else {
            // id valide

            $animal = Animal::exists($id);

            if (!$animal) {
                $errors[] = 'Aucun animal avec cet id';
            } else {
                // animal trouvé

                if (Animal::delete($animal) === 1) {
                    $success[] = 'Animal supprimé !';
                }
            }

        }

        // redirect
        $_SESSION['errors']['addAnimal'] = $errors;
        $_SESSION['success']['addAnimal'] = $success;

        $this->redirect($this->getRouter()->url('editAnimauxGET'));
    }
}