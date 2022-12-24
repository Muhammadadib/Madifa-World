<?php

namespace src\controllers;

require_once "core/Controller.php";
require_once "models/UserModel.php";

use src\core\Controller;
use src\models\UserModel;

class AdminController extends Controller {

    public function showUsersPage() {

        $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
        if (!$isAdmin) {
            $this->defaultRedirect();
        }
        else {
            $this->view("admin/users");
        }
    }

    public function getPaginatedUserData() {

        $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
        if (!$isAdmin) {
            http_response_code(404);
            die();
        }
        else {
            $page = 1;
            if (isset($_GET["page"])) $page = $_GET["page"];
    
            $rowPerPage = 20;
    
            $users = (new UserModel())->selectAll($page, $rowPerPage);
    
            echo json_encode($users);
        }
    }
}

?>