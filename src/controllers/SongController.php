<?php

namespace src\controllers;

require_once "core/Controller.php";
require_once "models/SongModel.php";
require_once "models/AlbumModel.php";

use src\core\Controller;
use src\models\SongModel;
use src\models\AlbumModel;

class SongController extends Controller {

    public function showSongDetail(int $songId) {

        $song = (new SongModel())->selectById($songId);

        if (!$song) $this->defaultRedirect();

        $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
        if ($isAdmin) {
            $albums = (new AlbumModel())->selectAllNoPagination();
            $this->view("admin/songDetail", [
                "song" => $song,
                "albums" => $albums
            ]);
        }
        else {
            $this->view("song/detail", [
                "song" => $song
            ]);
        }
    }

    public function showSongCreationForm() {

        $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
        if (!$isAdmin) {
            $this->defaultRedirect();
        }
        else {
            $albums = (new AlbumModel())->selectAllNoPagination();
            $this->view("song/create", [
                "albums" => $albums
            ]);
        }   
    }

    public function createSong() {

        $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
        if (!$isAdmin) {
            http_response_code(403);
            die();
        }
        else {
            $id = (new SongModel())->createSong($_POST, $_FILES);
            if ($id) {
                $this->redirectTo("/songs/" . (string)$id);
            }
            else {
                http_response_code(400);
                die();
            }
            $this->redirectTo("/songs");
        }
    }

    public function updateSong(int $songId) {

        $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
        if (!$isAdmin) {
            http_response_code(403);
            die();
        }

        $songModel = new SongModel();
        $song = $songModel->selectById($songId);

        if (!$song) $this->redirectTo("/songs");
        
        $songModel->updateSong($songId, $_POST, $_FILES);

        $this->redirectTo("/songs/" . (string)$songId);
    }

    public function deleteSong(int $songId) {

        $isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;
        if (!$isAdmin) {
            http_response_code(403);
            die();
        }
        
        $songModel = new SongModel();
        $song = $songModel->selectById($songId);

        if (!$song) $this->redirectTo("/songs");

        $songModel->deleteSong($songId);

        $this->redirectTo("/");
    }

    public function showSongs() {

        $genres = (new SongModel())->getAllGenres();
        if (!$genres) $genres = [];

        if (isset($_GET["searchkey"])) {
            $this->view("song/index", [
                "searchkey" => $_GET["searchkey"],
                "genres" => $genres
            ]);
        }
        else {
            $this->view("song/index", [
                "genres" => $genres
            ]);
        }
    }

    public function getPaginatedSongData() {

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

        $songs = (new SongModel())->selectAll(
            $page,
            $rowPerPage,
            $searchKey,
            $base,
            $titlesort,
            $yearsort,
            $genre
        );

        echo json_encode($songs);
    }

    public function getSongHomePageData()
    {
        $song = new SongModel();
        $data = $song->getSongHomePage();
        echo json_encode($data);   
    }
}

?>