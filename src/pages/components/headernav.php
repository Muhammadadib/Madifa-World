<?php 
    $isLoggedIn = isset($_SESSION["loggedInUser"]);
    $userType = !$isLoggedIn ? "" : ($_SESSION["isAdmin"] ? "Admin" : "User");
?>

<nav>
    <a class="blueSound-anchor center">
        <h1>Madifa World</h1>
    </a>
    <div class="menu-options">
        <a class="menu" href="/">
            <img src="../storage/propertiesImage/home.svg" alt="search-icon">
            <p>Home</p>
        </a>
        <a class="menu" href="/albums">
            <img src="../storage/propertiesImage/disc.svg" alt="album-icon">
            <p>List Album</p>
        </a>

        <?php if ($userType == "Admin"): ?>
            <a class="menu" href="/users">
                <img src="../storage/propertiesImage/users.svg" alt="add-icon">
                <p>User List</p>
            </a>
            <a class="menu" href="/albums/add">
                <img src="../storage/propertiesImage/plus-circle.svg" alt="add-icon">
                <p>Add Album</p>
            </a>
            <a class="menu" href="/songs/add">
                <img src="../storage/propertiesImage/plus-circle.svg" alt="add-icon">
                <p>Add Song</p>
            </a>
        <?php endif; ?>
    </div>
</nav>

<header class="flex-justify-between">
    <div class="search-bar">
        <img class="search-icon center" src="../storage/propertiesImage/search.svg" alt="search-icon">
        <form class="search-input-form center" id="song-search-form" method="get" action="/songs">
            <input class="search-input" type="text" name="searchkey" id="song-search" placeholder="What do you want to listen to?">
        </form>
    </div>

    <div id="header-auth-div">
        <?php if ($isLoggedIn): ?>
            <?php $greet = "<h3> Hello, ".$_SESSION["loggedInUser"]. " (" . $userType .")" ."</h3>"; ?>
            <div class="account-bar center">
                <h2><?php echo $greet?></h2>
            </div>
            <form method="post" action="/logout">
                <input type="submit" class="header-auth-button" value="Logout" name="logout"/>
            </form>
        <?php else: ?>
            <form method="get" action="/login">
                <input type="submit" class="header-auth-button" value="Login"/>
            </form>
            <form method="get" action="/register">
                <input type="submit" class="header-auth-button" value="Register"/>
            </form>
        <?php endif; ?>
    </div>
</header>

<script>
    let songSearch = document.getElementById("song-search");
    songSearch.addEventListener("keypress", function(event) {
        if (event.key == "Enter") {
            document.getElementById("song-search-form").submit();
        }
    })
</script>



