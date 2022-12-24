<?php

namespace src\routes;

require_once "core/Routes.php";

use src\core\Routes;

class AdminRoutes extends Routes {
    protected function defineRoutes(): void {
        $this->get("users", "AdminController", "showUsersPage");
        $this->get("api/users", "AdminController", "getPaginatedUserData");
    }
}

?>