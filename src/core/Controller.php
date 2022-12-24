<?php

namespace src\core;

abstract class Controller {
    protected function view(string $view, array $data = []): void {
        require_once "pages/" . $view . ".php";
    }

    protected function defaultRedirect() {
        // Redirects to default path ( / )
        header("Location: /");
        die();
    }

    protected function redirectTo(string $path) {
        // Redirects to specified path
        header("Location: " . $path);
        die();
    }

    
}

?>