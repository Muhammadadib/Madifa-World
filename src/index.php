<?php

require_once "core/Router.php";

use src\core\Router;

session_start();

$router = new Router();

$router->handleRequest();

?>