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
        dependenciesGenerator([], ["../style/albumDetail.css"]);
    ?>

    <div class="contents">
        <h1>Detail Album</h1>
        <div class="main-container">
        <div class="details">
        <h1><?php echo $data["album"]["judul"]?></h1>
        <div class="imageAlbum">
            <img src="/storage/<?php echo $data["album"]["image_path"]?>">
        </div>
        <br>
        <p>Artist : <?php echo $data["album"]["penyanyi"]?></p>
        <p>Release Date : <?php echo $data["album"]["tanggal_terbit"]?></p>
        <p>Genre : <?php echo $data["album"]["genre"]?></p>
        <p>Total Duration : 
            <?php 
                $minutes = intdiv($data["album"]["total_duration"], 60);
                $seconds = $data["album"]["total_duration"] % 60;
                echo $minutes . " m " . $seconds . " s";
            ?>
        </p>
        <p>
            <h2>
            List of songs: 
            </h2>
            <?php
                $no = 1;
                foreach ($data["songs"] as $song) {
                    echo '<a href="/songs/' . (string)$song["song_id"] . '">';
                    echo '<h2>';
                    echo $no . ". ";
                    echo $song["judul"];
                    echo '</h2>';
                    $no++;
                }
            ?>
        </p>
        <br>
        </div>
        </div>
    </div>
</body>
</html>