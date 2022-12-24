<?php

namespace src\models;

require_once "core/Database.php";

use src\core\Database;

class SongModel {
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function selectAll($currentPage, 
        $rowPerPage, 
        $searchKey = "", 
        $base = "all", 
        $titleSort = 0, 
        $yearSort = 0,
        $genre = []) {

        // Returns array containing data of song and totalPages
        // Song data: song_id, judul, penyanyi, tahun terbit, genre
        // Paginated, max $rowPerPage users in each page

        $query = "SELECT song_id, judul, penyanyi, YEAR(tanggal_terbit) as tahun_terbit, genre
            FROM song ";

        if ($genre || $searchKey) {
            $query .= "WHERE ";
        }

        if ($genre) {
            $query .= "(";

            $genreLength = count($genre);
            for ($i = 0; $i < $genreLength; $i++) {
                $query .= "genre = '" . $genre[$i];
                if ($i != $genreLength - 1) {
                    $query .= "' OR ";
                }
                else {
                    $query .= "') ";
                }
            }

            if ($searchKey) {
                $query .= " AND ";
            }
        }

        if ($searchKey) {
            if ($base == "title") {
                $query .= "judul LIKE CONCAT('%', :searchKey, '%') ";
            }
            else if ($base == "artist") {
                $query .= "penyanyi LIKE CONCAT('%', :searchKey, '%') ";
            }
            else if ($base == "year") {
                $query .= "YEAR(tanggal_terbit) LIKE CONCAT('%', :searchKey, '%') ";
            }
            else {
                $query .= "(judul LIKE CONCAT('%', :searchKey, '%') OR penyanyi LIKE CONCAT('%', :searchKey, '%') OR YEAR(tanggal_terbit) LIKE CONCAT('%', :searchKey, '%')) ";
            }
        }

        if ($titleSort != 0) {
            $query .= "ORDER BY judul ";
            if ($titleSort == 1) {
                $query .= "ASC, ";
            }
            else {
                $query .= "DESC, ";
            }

            $query .= "YEAR(tanggal_terbit) ";

            if ($yearSort == 0 || $yearSort == 1) {
                $query .= "ASC ";
            }
            else {
                $query .= "DESC ";
            }
        }
        else {
            $query .= "ORDER BY YEAR(tanggal_terbit) ";
            if ($yearSort == 0 || $yearSort == 1) {
                $query .= "ASC, ";
            }
            else {
                $query .= "DESC, ";
            }
            $query .= "judul ASC";
        }
        
        $this->db->prepare($query);
        if ($searchKey) {
            $this->db->bind(":searchKey", $searchKey);
        }
        $this->db->execute();
        $totalRows = $this->db->rowCount();
        $totalPages = (int)ceil($totalRows / $rowPerPage);

        if (!$totalRows) return array (
            "data" => array(),
            "totalPages" => 0
        );

        $offset = ($currentPage-1) * $rowPerPage;
        $paginatedQuery = $query . " LIMIT " . $rowPerPage . " OFFSET " . $offset;

        $this->db->prepare($paginatedQuery);
        if ($searchKey) {
            $this->db->bind(":searchKey", $searchKey);
        }
        $this->db->execute();
        $songs = $this->db->fetchAll();

        if (!$songs) {
            return array(
                "data" => array(),
                "totalPages" => $totalPages
            );
        } else {
            return array(
                "data" => $songs,
                "totalPages" => $totalPages
            );
        } 
    }

    public function selectById($id) {
        // Returns data of song with the corresponding id and its album's name
        $query = "SELECT song_id, 
            s.judul as judul_lagu, 
            penyanyi, 
            tanggal_terbit, 
            genre, duration, 
            audio_path,
            image_path, 
            s.album_id, 
            a.judul as judul_album
            FROM 
                (SELECT * 
                    FROM song
                    WHERE song_id = :id) as s
                LEFT JOIN
                (SELECT album_id, judul
                    FROM album) as a
                ON s.album_id = a.album_id
                ";

        $this->db->prepare($query);
        $this->db->bind(":id", $id);
        $this->db->execute();

        return $this->db->fetch();
    }

    public function getAllGenres() {
        // Returns an array of all genres
        
        $query = "SELECT DISTINCT genre
            FROM song";

        $this->db->prepare($query);
        $this->db->execute();
        $rows = $this->db->fetchAll();

        if (!$rows) return [];

        $genres = [];

        foreach($rows as $row) {
            array_push($genres, $row["genre"]);
        }

        return $genres;
    }

    public function createSong($songData, $fileData) {
        // Creates a new song based on songData and fileData

        // Check if required fields are missing
        if (!isset($songData["judul"]) || !isset($songData["penyanyi"]) 
            || !isset($songData["tanggal_terbit"]) || !isset($songData["genre"]) 
            || !isset($songData["album_id"]) || !isset($fileData["audio"]) 
            || !isset($fileData["image"])) {
            return false;
        }

        if ($fileData["image"]["size"] == 0 || $fileData["audio"]["size"] == 0) {
            return false;
        }

        if ($fileData["image"]["type"] != "image/jpeg" && $fileData["audio"]["type"] != "image/png") {
            return false;
        }

        // Create row in database
        $createQuery = "INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, album_id, duration, image_path, audio_path)
            VALUES (:judul, :penyanyi, :tanggal_terbit, :genre, :album_id, 0, 'x', 'x')";

        $this->db->prepare($createQuery);
        $this->db->bind(":judul", $songData["judul"]);
        $this->db->bind(":penyanyi", $songData["penyanyi"]);
        $this->db->bind(":tanggal_terbit", $songData["tanggal_terbit"]);
        $this->db->bind(":genre", $songData["genre"]);
        if ($songData["album_id"] == "NULL") {
            $this->db->bind(":album_id", NULL);
        }
        else {
            $this->db->bind(":album_id", $songData["album_id"]);
        }
        $this->db->execute();

        $id = $this->db->lastInsertId();
        if (!$id) return false;

        // Upload image
        $songData["image_path"] = "songImage/" . (string)($id) . date("-d-m-Y-") . (string)(rand(1000,9999)) . "." . pathinfo($fileData["image"]["name"], PATHINFO_EXTENSION);
        move_uploaded_file($fileData["image"]["tmp_name"], "./storage/" . $songData["image_path"]);

        // Upload audio
        $songData["audio_path"] = "songAudio/" . (string)($id) . date("-d-m-Y-") . (string)(rand(1000,9999)) . "." . pathinfo($fileData["audio"]["name"], PATHINFO_EXTENSION);
        move_uploaded_file($fileData["audio"]["tmp_name"], "./storage/" . $songData["audio_path"]);
        
        $audioInfo = shell_exec("ffmpeg -i storage/" . $songData["audio_path"] . " 2>&1");
        preg_match("/Duration: (.{2}):(.{2}):(.{2})/", $audioInfo, $duration);
        $songData["duration"] = (int)$duration[1] * 3600 + (int)$duration[2] * 60 + (int)$duration[3];

        // Update path and duration
        $updateQuery = "UPDATE song SET 
            image_path = :image_path,
            audio_path = :audio_path,
            duration = :duration
            WHERE song_id = :id";
        $this->db->prepare($updateQuery);
        $this->db->bind(":image_path", $songData["image_path"]);
        $this->db->bind(":audio_path", $songData["audio_path"]);
        $this->db->bind(":duration", $songData["duration"]);
        $this->db->bind(":id", $id);
        $this->db->execute();

        // Update album total duration
        if ($songData["album_id"] != "NULL") {
            $updateAlbumQuery = "UPDATE album SET total_duration = total_duration + :duration WHERE album_id = :id";
            $this->db->prepare($updateAlbumQuery);
            $this->db->bind(":duration", $songData["duration"]);
            $this->db->bind(":id", $songData["album_id"]);
            $this->db->execute();
        }

        return $id;
    }

    public function updateSong($id, $newSongData, $fileData) {
        // Updates song with the corresponding id
        // Changes the duration of album related to song depending on new song duration

        $songQuery = "SELECT duration, audio_path, song.image_path, song.album_id
            FROM song
            WHERE song_id = :id";

        $this->db->prepare($songQuery);
        $this->db->bind(":id", $id);
        $this->db->execute();

        $songData = $this->db->fetch();

        if (!$songData) return false;

        // Check if required fields are missing
        if (!isset($newSongData["judul"]) || !isset($newSongData["tanggal_terbit"]) || !isset($newSongData["genre"]) || !isset($newSongData["album_id"])) {
            return false;
        }

        // Upload image
        if ($fileData["image"]["size"] != 0) {
            if ($fileData["image"]["type"] != "image/jpeg" && $fileData["audio"]["type"] != "image/png") {
                return false;
            }
            else {
                unlink("./storage/" . $songData["image_path"]);

                $newSongData["image_path"] = "songImage/" . (string)($id) . date("-d-m-Y-") . (string)(rand(1000,9999)) . "." . pathinfo($fileData["image"]["name"], PATHINFO_EXTENSION);
                move_uploaded_file($fileData["image"]["tmp_name"], "./storage/" . $newSongData["image_path"]);
            }
        }

        // Upload audio
        if ($fileData["audio"]["size"] != 0) {
            if ($fileData["audio"]["type"] != "audio/mpeg" && $fileData["audio"]["type"] != "audio/wav") {
                return false;
            }
            else {
                unlink("./storage/" . $songData["audio_path"]);

                $newSongData["audio_path"] = "songAudio/" . (string)($id) . date("-d-m-Y-") . (string)(rand(1000,9999)) . "." . pathinfo($fileData["audio"]["name"], PATHINFO_EXTENSION);
                move_uploaded_file($fileData["audio"]["tmp_name"], "./storage/" . $newSongData["audio_path"]);
                
                $audioInfo = shell_exec("ffmpeg -i storage/" . $newSongData["audio_path"] . " 2>&1");
                preg_match("/Duration: (.{2}):(.{2}):(.{2})/", $audioInfo, $duration);
                $newSongData["duration"] = (int)$duration[1] * 3600 + (int)$duration[2] * 60 + (int)$duration[3];
            }
        }

        $updateSongQuery = "UPDATE song SET 
            judul = :judul,
            tanggal_terbit = :tanggal_terbit,
            genre = :genre,
            album_id = :album_id";

        if ($fileData["image"]["size"] != 0) {
            $updateSongQuery .= ", image_path = :image_path";
        }

        if ($fileData["audio"]["size"] != 0) {
            $updateSongQuery .= ", audio_path = :audio_path, duration = :duration";
        }

        $updateSongQuery .= " WHERE song_id = :song_id";

        $this->db->prepare($updateSongQuery);
        $this->db->bind(":judul", $newSongData["judul"]);
        $this->db->bind(":tanggal_terbit", $newSongData["tanggal_terbit"]);
        $this->db->bind(":genre", $newSongData["genre"]);
        if ($newSongData["album_id"] == "NULL") {
            $this->db->bind(":album_id", NULL);
        }
        else {
            $this->db->bind(":album_id", $songData["album_id"]);
        }
        $this->db->bind(":song_id", $id);

        if ($fileData["image"]["size"] != 0) {
            $this->db->bind(":image_path", $newSongData["image_path"]);
        }

        if ($fileData["audio"]["size"] != 0) {
            $this->db->bind(":audio_path", $newSongData["audio_path"]);
            $this->db->bind(":duration", $newSongData["duration"]);
        }

        $this->db->execute();

        // Update album total duration
        if ($songData["album_id"] != $newSongData["album_id"] || $fileData["audio"]["size"] != 0) {
            $updateAlbumQuery = "UPDATE album SET total_duration = total_duration - :duration WHERE album_id = :id";
            $this->db->prepare($updateAlbumQuery);
            $this->db->bind(":duration", $songData["duration"]);
            $this->db->bind(":id", $songData["album_id"]);
            $this->db->execute();

            $duration = $fileData["audio"]["size"] != 0 ? $newSongData["duration"] : $songData["duration"];
            $targetAlbum = $songData["album_id"] == $newSongData["album_id"] ? $songData["album_id"] : $newSongData["album_id"];

            if ($targetAlbum != "NULL") {
                $updateAlbumQuery = "UPDATE album SET total_duration = total_duration + :duration WHERE album_id = :id";
                $this->db->prepare($updateAlbumQuery);
                $this->db->bind(":duration", $duration);
                $this->db->bind(":id", $targetAlbum);
                $this->db->execute();
            }
        }

        return true;
    }

    public function deleteSong($id) {
        // Deletes song with the corresponding id
        // Reduces the duration of album related to song by the song's duration

        $songQuery = "SELECT duration as duration, audio_path, image_path, album_id
            FROM song
            WHERE song_id = :id";

        $this->db->prepare($songQuery);
        $this->db->bind(":id", $id);
        $this->db->execute();

        $songData = $this->db->fetch();

        if (!$songData) return false;

        unlink("./storage/" . $songData["image_path"]);
        unlink("./storage/" . $songData["audio_path"]);

        $deleteQuery = "DELETE FROM song WHERE song_id = :id";

        $this->db->prepare($deleteQuery);
        $this->db->bind(":id", $id);
        $this->db->execute();

        if ($songData["album_id"]) {
            $updateQuery = "UPDATE album
                SET total_duration = total_duration - :duration
                WHERE album_id = :id";

            $this->db->prepare($updateQuery);
            $this->db->bind(":duration", $songData["duration"]);
            $this->db->bind(":id", $songData["album_id"]);
            $this->db->execute();
        }
        
        return true;
    }

    public function getSongHomePage()
    {
        //10 alphabetically ordered and most recent songs
        $query = "SELECT * 
            FROM (SELECT song_id, judul, YEAR(tanggal_terbit) as tahun_terbit, penyanyi, genre, image_path
            FROM song
            ORDER BY tanggal_terbit DESC
            LIMIT 10) as s
            ORDER BY judul ASC";

        $this->db->prepare($query);
        $data = $this->db->fetchAll();
        return $data;
    }

    public function getSongByAlbumId($album_id)
    {
        $query = "SELECT * FROM song WHERE album_id = :album_id";
        $this->db->prepare($query);
        $this->db->bind(":album_id", $album_id);
        $data = $this->db->fetchAll();
        return $data;
    }
}