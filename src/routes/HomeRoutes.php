<?php

namespace src\routes;

require_once "core/Routes.php";

use src\core\Routes;

class HomeRoutes extends Routes {
    protected function defineRoutes(): void {
        $this->get("", "HomeController", "showHomePage");
        $this->patch("", "HomeController", "showHomePage");
        $this->post("", "HomeController", "postCallback");
    }
}

?>