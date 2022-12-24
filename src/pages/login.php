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
      dependenciesGenerator([], ["../style/auth.css"]);
  ?>
</head>
<body>
  <?php require_once "components/headernav.php"; ?>
  <div class="contents">
    <div class="main-container">
    <h1>Login to Madifa World</h1>
      <form method=POST>
        <div class="input-field-div">
          <label for="username" style="color:white">Username</label><br>
          <input type="text" class="input-field" id="input-form" name="username">
        </div>
        <div class="input-field-div">
          <label for="password" style="color:white">Password</label>
          <br> 
          <input type="password" class="input-field" id="input-form" name="password"> <br>
          <br>
        </div>
        <div class="submit-form-div">
          <input type="submit" id="submit-form" value="Log in">
        </div>
        <div class="border"></div>
        <p class="footer-text">Don't have an account?</p>
        <button type="button" class="footer-button" onclick="location.href='/register'">Register to BlueSound</button>
      </form>
    </div>
  </div>
</body>
</html>
