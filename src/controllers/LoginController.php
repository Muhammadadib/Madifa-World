<?php

namespace src\controllers;

require_once "core/Controller.php";
require_once "models/UserModel.php";

use src\core\Controller;
use src\models\UserModel;

class LoginController extends Controller {
    public function showLoginPage() {
        // GET /
        if (isset($_SESSION['loggedInUser']))
        {
            $this->redirectTo("home");
        }
        else
        {
            $this->view("login");
        }
    }

    public function loginCheck() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = new UserModel();
        if (!isset($_SESSION['loggedInUser']) && $user->isUserExist($username, $password))
        {
            $_SESSION['loggedInUser'] = $username;
            $_SESSION['isAdmin'] = $user->isAdmin($username);
            $this->redirectTo("home");
        }
        else
        {
            $this->view("login");
        }
    }   

    public function logout() {
        
        if (!isset($_SESSION["loggedInUser"])) {
            $this->defaultRedirect();
        }
        else {
            session_destroy();
            $this->redirectTo("/");
        }
    }
}

?>