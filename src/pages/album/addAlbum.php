<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madifa World</title>
    <script>
      function validate() { 
            title = document.getElementById('title').value; 
            singer = document.getElementById('singer').value;
            image_path = document.getElementById('image-path').value;
            release_date = document.getElementById('release-date').value;
            genre = document.getElementById('genre').value;
            valid = title.length !== 0 
                    && singer.length !== 0
                    && image_path.length !== 0
                    && release_date.length !== 0
                    && genre.length !== 0
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
    <?php
        require_once("./utils/Getter.php");
        require_once "./pages/components/dependenciesIncluder.php";
        addHeaderNavDependencies();
        dependenciesGenerator([], ["../style/form.css"]);  
    ?>
  </head>
  

  <body>
    <?php require_once "./pages/components/headernav.php"; ?>
    <div class="contents">
      <div class="main-container">
        <h1>Add Album</h1>
        <form method=POST>
        <label for="title">Title</label>
        <br>
        <input type="text" id="title" name="title" onkeyup="validate()"> 
        <br>
        <br>
        <label for="singer">Singer</label>
        <br>
        <input type="text" id="singer" name="singer" onkeyup="validate()">
        <br>
        <br>
        <label for="image-path">Image Path</label>
        <br>
        <input type="file" id="image-path" name="image-path" onchange="validate()"> 
        <br>
        <br>
        <label for="release-date">Release Date</label>
        <br>
        <input type="date" id="release-date" name="release-date" onchange="validate()"> 
        <br>
        <br>
        <label for="genre">Genre</label>
        <br>
        <input type="text" id="genre" name="genre" onkeyup="validate()"> 
        <br>
        <br>
        <input type="submit" value="Add Album" id="submit-form">
      </div>
    </div>
  </body>
</html>
