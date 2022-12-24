<?php

namespace src\routes;

require_once "core/Routes.php";

use src\core\Routes;

class RegisterRoutes extends Routes {
    protected function defineRoutes(): void {
        // GET method setup
        $this->get("register", "RegisterController", "showRegisterPage");

        // POST method setup
        $this->post("register", "RegisterController", "registerSubmit");

        // GET
        $this->get("api/register/checkUsername", "RegisterController", "checkUsername");

        //GET
        $this->get("api/register/checkEmail", "RegisterController", "checkEmail");
    }
}

?>