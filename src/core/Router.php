<?php

namespace src\core;

class Router {

    function handleRequest(): void {
        // Iterates through routes to find a handler for the corresponding route
        $path = "";
        if (isset($_GET["url"])) {
            $path = $_GET["url"];
        }

        $method = $_SERVER["REQUEST_METHOD"];
        if ($method == "POST") {
            if (isset($_POST["_method"])) {
                if ($_POST["_method"] == "PUT") {
                    $method = "PUT";
                }
                else if ($_POST["_method"] == "PATCH") {
                    $method = "PATCH";
                }
                else if ($_POST["_method"] == "DELETE") {
                    $method = "DELETE";
                }
            }
        }

        $handlerFound = false;

        foreach(glob("routes/*.php") as $fileRoute) {
            require_once $fileRoute;
            
            $className = explode("/", explode(".", $fileRoute)[0])[1];

            if ((new ("src\\routes\\" . $className)())->handleRoute($method, $path)) $handlerFound = true;
        }

        if (!$handlerFound) {
            if ($method == "GET") {
                header("Location: /");
                die();
            }
            else {
                http_response_code(404);
                die();
            }
        }
    }
}

?>