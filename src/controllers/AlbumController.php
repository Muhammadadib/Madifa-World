<?php

namespace src\controllers;

require_once "core/Controller.php";
require_once "models/AlbumModel.php";
require_once "models/SongModel.php";

use src\core\Controller;
use src\models\AlbumModel;
use src\models\SongModel;

class AlbumController extends Controller {

    public function showAllAlbum(){
        $albumModel = new AlbumModel();
        $album = $albumModel->selectAllAlbum();
        var_dump($album);

        $this->view("album/detail", [
        ]);
    }
    
    public function getPaginatedAlbumData() {

        $page = 1;
        if (isset($_GET["page"])) $page = $_GET["page"];

        $rowPerPage = 10;

        $searchKey = "";
        if (isset($_GET["searchkey"])) $searchKey = $_GET["searchkey"];

        $base = "all";
        if (isset($_GET["base"])) $base = $_GET["base"];

        $titlesort = "none";
        if (isset($_GET["titleorder"])) $titlesort = $_GET["titleorder"];

        $yearsort = "none";
        if (isset($_GET["yearorder"])) $yearsort = $_GET["yearorder"];

        $genre = [];
        if (isset($_GET["genre"]) && $_GET["genre"] != "") {
            $genre = explode(",", $_GET["genre"]);
        }

        $albums = (new AlbumModel())->selectAll(
            $page,
            $rowPerPage,
            $searchKey,
            $base,
            $titlesort,
            $yearsort,
            $genre
        );

        echo json_encode($albums);
    }

    // Create album from database
    public function createAlbum() {
        $data["album_id"] = $_POST["album_id"];
        $data["judul"] = $_POST["judul"];
        $data["penyanyi"] = $_POST["penyanyi"];
        $data["total_duration"] = 0;
        $data["image_path"] = $_POST["image_path"];
        $data["tanggal_terbit"] = $_POST["tanggal_terbit"];
        $data["genre"] = $_POST["genre"];

        $albumModel = new AlbumModel();
        $albumModel-> addAlbumToList($data);

        $this->defaultRedirect();
    }

    // Delete album from database
    public function deleteAlbum(){
        $albumModel = new AlbumModel();
        $albumModel->deleteAlbumFromList($_POST["album_id"]);

        $this->defaultRedirect();
    }

    // Update album from database
    public function updateAlbum(){
        $data["album_id"] = $_POST["album_id"];
        $data["judul"] = $_POST["judul"];
        $data["penyanyi"] = $_POST["penyanyi"];
        $data["total_duration"] = $_POST["total_duration"];
        $data["image_path"] = $_POST["image_path"];
        $data["tanggal_terbit"] = $_POST["tanggal_terbit"];
        $data["genre"] = $_POST["genre"];

        $albumModel = new AlbumModel();
        $albumModel->updateAlbumFromList($data);

        $this->defaultRedirect();
    }

    public function showAlbumDetail(int $albumId) {

        $album = (new AlbumModel())->selectById($albumId);
        $songs = (new SongModel())->getSongByAlbumId($albumId);

        if (!$album) $this->defaultRedirect();
        
        $this->view("album/detail", [
            "album" => $album,
            "songs" => $songs
        ]);
    }

    public function showAlbums(){

        $genres = (new AlbumModel())->getAllGenres();
        if (!$genres) $genres = [];
        
        if (isset($_GET["searchkey"])) {
            $this->view("album/index", [
                "searchkey" => $_GET["searchkey"],
                "genres" => $genres
            ]);
        }
        else {
            $this->view("album/index", [
                "genres" => $genres
            ]);
        }
    }

    public function showAddAlbumPage() {
        if (isset($_SESSION["loggedInUser"]) && $_SESSION["isAdmin"]) {
            $this->view("album/addAlbum");
        }
        else {
            $this->defaultRedirect();
        }
    }

    public function addAlbum() {

        if (isset($_SESSION["loggedInUser"]) && $_SESSION["isAdmin"]) {
            $data["judul"] = $_POST["title"];
            $data["penyanyi"] = $_POST["singer"];
            $data["total_duration"] = 0;
            $data["image_path"] = "albumImage/".$_POST["image-path"];
            $data["tanggal_terbit"] = $_POST["release-date"];
            $data["genre"] = $_POST["genre"];

            $albumModel = new AlbumModel();
            $albumModel->addAlbumToList($data);

            $this->defaultRedirect();
        }
        else {
            $this->defaultRedirect();
        }
    }
}

?>