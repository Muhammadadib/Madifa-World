<?php

namespace src\controllers;

require_once "core/Controller.php";
require_once "models/SongModel.php";

use src\core\Controller;
use src\models\SongModel;

class HomeController extends Controller {
    public function showHomePage() {
        $song = new SongModel();
        $songs = $song->getSongHomePage();
        $this->view("home", [
            "songs" => $songs
        ]);
    }

    public function postCallback() {
        
        if (isset($_POST['logout']))
        {
            unset($_SESSION['loggedInUser']);
            unset($_SESSION['isAdmin']);
            $this->redirectTo("home");
        }
        
        else if (isset($_POST['login']))
        {
            $this->redirectTo('login');
        }
        else if (isset($_POST['register']))
        {
            $this->redirectTo("register");
        }
    }
}

?>