<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madifa World</title>
    <?php 
        require_once "./pages/components/dependenciesIncluder.php";
        addHeaderNavDependencies();
        dependenciesGenerator([], ["../style/home.css"]);
    ?>
    <?php
        require_once("./utils/Getter.php");
        dependenciesGenerator([], ["../style/form.css"]);  
    ?>
  </head>
<body>
    <?php require_once "./pages/components/headernav.php"; 
        dependenciesGenerator([], ["../style/songDetail.css"]);
    ?>

    <div class="contents">
        <div class="main-container">
        <h1>Detail song</h1>
        <div class="details">
            <img src="/storage/<?php echo $data["song"]["image_path"]?>">
            <br>
            <br>
            <h2>Title: <?php echo $data["song"]["judul_lagu"]?></h2>
            <h2>Part of <a href="/albums/<?php echo $data["song"]["album_id"]?>"><?php echo $data["song"]["judul_album"]?></a></h2>
            <h2>Artist: <?php echo $data["song"]["penyanyi"]?></h2>
            <br>
            Release Date: <?php echo $data["song"]["tanggal_terbit"]?>
            <br>
            Genre: <?php echo $data["song"]["genre"]?>
            <br>
            Duration: <?php echo $data["song"]["duration"]?>s
            <br>
            <br>
            <?php if (isset($_SESSION["loggedInUser"]) || !isset($_COOKIE["played"]) || (int) $_COOKIE["played"] < 3): ?>
                <audio id="audio-control" controls>
                    <source src="/storage/<?php echo $data["song"]["audio_path"]?>">
                </audio>
            <?php else: ?>
                <h2>You have reached the maximum songs allowed to be played today. Log in or come back tommorow!</h2>
            <?php endif; ?>
        </div>
        </div>
    </div>

    <?php if (!isset($_SESSION["loggedInUser"])): ?>
        <input type="text" id="played" value="no" hidden>

        <script>
            function getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for(let i = 0; i <ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }

            audioControl = document.getElementById("audio-control");
            audioControl.onplay = function() {
                if (document.getElementById("played").value == "no") {
                    document.getElementById("played").value = "yes";

                    let currentPlayed = getCookie("played");

                    let date = new Date();
                    let midnight = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 23, 59, 59);
                    let expires = "; expires=" + midnight.toGMTString();

                    if (currentPlayed == "") {
                        document.cookie = "played=1" + expires + "; path=/";
                    }
                    else {
                        document.cookie = "played=" + (Number(currentPlayed)+1) + expires + "; path=/";
                    }
                }
            }
        </script>
    <?php endif; ?>
</body>
</html>