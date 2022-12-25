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
    <h1>Edit Album with id <?php echo $data["album"]["album_id"]?></h1>
    <br>
    <div class="main-container">
    <div class="details-admin">
        <img src="/storage/<?php echo $data["album"]["image_path"]?>">
        <br>
        <form method="POST" action="/albums/<?php echo $data["album"]["album_id"]?>" enctype="multipart/form-data">
            <input type="text" name="_method" value="PUT" hidden>
            Title: <input type="text" name="judul" value="<?php echo $data["album"]["judul"]?>">
            <br>
            Artist: <?php echo $data["album"]["penyanyi"]?>
            <br>
            Release Date: <input type="date" name="tanggal_terbit" value="<?php echo $data["album"]["tanggal_terbit"]?>">
            <br>
            Genre: <input type="text" name="genre" value="<?php echo $data["album"]["genre"]?>">
            <br>
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
            <button type="submit">Update</button>
            <input type="text" name="_method" value="DELETE" hidden>
            <button type="submit">Delete</button>
        </form>

        <!-- <form method="POST" action="/albums/<?php echo $data["album"]["album_id"]?>">
            <input type="text" name="_method" value="DELETE" hidden>
            <button type="submit">Delete</button>
        </form> -->
    </div>
    </div>
    </div>
</body>