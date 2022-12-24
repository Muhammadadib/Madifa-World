<?php

namespace src\routes;

require_once "core/Routes.php";

use src\core\Routes;

class SongRoutes extends Routes {
    protected function defineRoutes(): void {
        $this->get("songs/(?P<songId>\d+)", "SongController", "showSongDetail");
        $this->put("songs/(?P<songId>\d+)", "SongController", "updateSong");
        $this->delete("songs/(?P<songId>\d+)", "SongController", "deleteSong");
        
        $this->get("songs", "SongController", "showSongs");
        $this->get("api/songs", "SongController", "getPaginatedSongData");
        $this->get("api/songs/home", "SongController", "getSongHomePageData");

        $this->get("songs/add", "SongController", "showSongCreationForm");
        $this->post("songs", "SongController", "createSong");
    }
}

?>