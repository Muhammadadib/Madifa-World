<?php

namespace src\routes;

require_once "core/Routes.php";

use src\core\Routes;

class LoginRoutes extends Routes {
    protected function defineRoutes(): void {
        //           url                controller       callback function
        // GET method setup
        $this->get("login", "LoginController", "showLoginPage");

        // POST method setup
        $this->post("login", "LoginController", "loginCheck");

        $this->post("logout", "LoginController", "logout");
    }
}

?>