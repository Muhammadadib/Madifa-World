<?php
    function stylesheetGenerator($stylesheet_directory) {
        echo "<link rel='stylesheet' href=" . $stylesheet_directory . ">";
    }

    function scriptGenerator($script_directory) {
        echo "<script type='text/javascript' src=" . $script_directory . "></script>";
    }

    // $script_directory, $stylsheet_directories: array of string
    function dependenciesGenerator($script_directories, $stylesheet_directories) {
        foreach($script_directories as $dir) {
            scriptGenerator($dir);
        }

        foreach($stylesheet_directories as $dir) {
            stylesheetGenerator($dir);
        }
    }
?>