<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madifa World</title>
    <?php 
        require_once "components/dependenciesIncluder.php";
        addHeaderNavDependencies();
        dependenciesGenerator([], ["../style/home.css"]);
    ?>
</head>
<body>
    <?php 
        require_once "components/headernav.php";
    ?>
    <div class="contents">
        <table class="song-lists" id="song-lists">
            <tbody id="dynamic-song-container">
                <div class=row>
                <?php $data["songs"][0]["judul"];
                    foreach ($data["songs"] as $song) {
                        $image = "<img src='/storage/" . $song["image_path"] . "' height= 200px width= 200px>";
                        echo '<a href="/songs/' . (string)$song["song_id"] . '">';
                        echo '<div class=column>';
                        echo $image;
                        echo '<div class="font-song">';
                        echo $song["judul"];
                        echo " - ";
                        echo $song['penyanyi'];
                        echo "<br>";
                        echo $song["tahun_terbit"];
                        echo " - ";
                        echo $song['genre'];
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                ?>
                </div>
            </tbody>
        </table> 
    </div>
</body>
</html>