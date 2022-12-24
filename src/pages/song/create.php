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
    ?>
    <?php
        require_once("./utils/Getter.php");
        dependenciesGenerator([], ["../style/form.css"]);  
    ?>
  </head>
  <body>
    <?php require_once "./pages/components/headernav.php"; ?>
    <div class="contents">
      <div class="main-container">
        <h1>Add Song</h1>
        <form method=POST action="/songs" enctype="multipart/form-data">
        <label for="title">Title</label>
        <br>
        <input type="text" id="title" name="judul" onkeyup="validate()"> 
        <br>
        <br>
        <label for="album">Album</label>
        <br>
        <select id="album_id" name="album_id" onchange="validate()">
              <option value="NULL">-</option>
              <?php foreach ($data["albums"] as $album): ?>
                  <option value="<?php echo $album["album_id"]?>">
                      <?php echo $album["judul"]?>
                  </option>
              <?php endforeach; ?>
          </select> 
        <br>
        <br>
        <label for="artist">Artist</label>
        <br>
        <input type="text" id="artist" name="penyanyi" onkeyup="validate()"> 
        <br>
        <br>
        <label for="release-date">Release Date</label>
        <br>
        <input type="date" id="release-date" name="tanggal_terbit" onchange="validate()"> 
        <br>
        <br>
        <label for="genre">Genre</label>
        <br>
        <input type="text" id="genre" name="genre" onkeyup="validate()"> 
        <br>
        <br>
        <label for="image-path">Image</label>
        <br>
        <input type="file" id="image-path" name="image" onchange="validate()"> 
        <br>
        <br>
        <label for="audio-path">Audio</label>
        <br>
        <input type="file" id="audio-path" name="audio" onchange="validate()"> 
        <br>
        <br>
        <input type="submit" value="Add Song" id="submit-form">
      </div>
    </div>
    
    <script>
      function validate() { 
            title = document.getElementById('title').value; 
            album_id = document.getElementById('album_id').value;
            artist = document.getElementById('artist').value;
            release_date = document.getElementById('release-date').value;
            genre = document.getElementById('genre').value;
            image_path = document.getElementById('image-path').value;
            audio_path = document.getElementById('audio-path').value;
            valid = title.length !== 0 
                    && album_id.length !== 0
                    && artist.length !== 0
                    && release_date.length !== 0
                    && genre.length !== 0
                    && image_path.length !== 0
                    && audio_path.length !== 0
            console.log(valid);
            if (valid)
            {
            document.getElementById("submit-form").disabled = false;
            document.getElementById("submit-form").enabled = true;
            }
            else
            {
            document.getElementById("submit-form").enabled = false;
            document.getElementById("submit-form").disabled = true;
            }
      }

      window.onload = function() {
          validate();
        }
    </script>
  </body>

</html>
