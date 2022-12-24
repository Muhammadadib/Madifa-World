<?php
    require_once "./utils/Getter.php";
    function addHeaderNavDependencies() {
        dependenciesGenerator([], ["../style/global.css", "../style/home.css"]);
    }
?>