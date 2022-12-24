<?php

namespace src\models;

require_once "core/Database.php";

use src\core\Database;

class AlbumModel {
    public static $table = 'album';
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function selectAllNoPagination() {
        // Returns array containing id and name of all albums

        $query = "SELECT album_id, judul
            FROM album
            ORDER BY judul ASC";

        $this->db->prepare($query);
        $this->db->execute();
        $albums = $this->db->fetchAll();

        if (!$albums) {
            return [];
        }
        else {
            return $albums;
        }
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

        $query = "SELECT album_id, judul, penyanyi, YEAR(tanggal_terbit) as tahun_terbit, genre
            FROM album ";

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

    public function selectAllAlbum(){
        $query = "SELECT judul, penyanyi, tanggal_terbit, genre 
                FROM " . AlbumModel::$table . 
                "ORDER BY judul ASC, tanggal_terbit DESC 
                LIMIT 10";

        $this->db->prepare($query);

        $this->db->execute();

        return $this->db->fetchAll();
    }

    public function selectDaftarAlbumById($id) {
        $query = "SELECT judul, penyanyi, tanggal_terbit, genre 
                FROM" . AlbumModel::$table . 
                "WHERE album_id = :id";

        $this->db->prepare($query);

        $this->db->bind(":album_id", $id);

        $this->db->execute();

        return $this->db->fetch();
    }

    public function selectDaftarAlbumOrderASCById($id) {
        $query = "SELECT judul, penyanyi, tanggal_terbit, genre 
                FROM " . AlbumModel::$table . "
                WHERE album_id = :id
                ORDER BY judul ASC LIMIT 10";

        $this->db->prepare($query);

        $this->db->bind(":album_id", $id);

        $this->db->execute();

        return $this->db->fetch();
    }

    public function selectById($id) {
        $query = "SELECT * 
                FROM " . AlbumModel::$table . "
                WHERE album_id = :album_id
                ORDER BY judul ASC";

        $this->db->prepare($query);

        $this->db->bind(":album_id", $id);

        $this->db->execute();

        return $this->db->fetch();
    }

    public function selectJudulAlbumOrderASCById($id) {
        $query = "SELECT judul 
                FROM " . AlbumModel::$table .  
                "WHERE album_id = :id
                ORDER BY judul ASC";

        $this->db->prepare($query);

        $this->db->bind(":album_id", $id);

        $this->db->execute();

        return $this->db->fetch();
    }

    public function addAlbumToList($data){
        $query = "INSERT INTO " . AlbumModel::$table .
                " VALUES (NULL, :judul, :penyanyi, :total_duration, :image_path, :tanggal_terbit, :genre)";

        $this->db->prepare($query);

        $this->db->bind(":judul", $data['judul']);

        $this->db->bind(":penyanyi", $data['penyanyi']);

        $this->db->bind(":total_duration", $data['total_duration']);

        $this->db->bind(":image_path", $data['image_path']);

        $this->db->bind(":tanggal_terbit", $data['tanggal_terbit']);

        $this->db->bind(":genre", $data['genre']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function deleteAlbumFromList($id) {
        $query = "DELETE FROM " . AlbumModel::$table .
               " WHERE album_id = :id";

        $this->db->prepare($query);

        $this->db->bind(':album_id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function updateAlbumFromList($data) {
        $query = "UPDATE " . AlbumModel::$table . " SET
                    judul = :judul,
                    penyanyi = :penyanyi,
                    total_duration = :total_duration,
                    image_path = :image_path,
                    tanggal_terbit = :tanggal_terbit,
                    genre = :genre
                WHERE album_id = :album_id";

        $this->db->prepare($query);

        $this->db->bind(":album_id", $data['album_id']);

        $this->db->bind(":judul", $data['judul']);

        $this->db->bind(":penyanyi", $data['penyanyi']);

        $this->db->bind(":total_duration", $data['total_duration']);

        $this->db->bind(":image_path", $data['image_path']);

        $this->db->bind(":tanggal_terbit", $data['tanggal_terbit']);

        $this->db->bind(":genre", $data['genre']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function getAllGenres(){
        $query = "SELECT DISTINCT genre
            FROM " . AlbumModel::$table;

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
    
}