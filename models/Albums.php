<?php
class Albums{
    private $connexion;
    private $table = "albums";
    private $table2 = "tracks";

    public $id;
    public $artist_id;
    public $description;
    public $cover;
    public $cover_small;
    public $release_date;
    public $popularity;

    public function __construct($db){
        $this->connexion = $db;
    }

    public function main(){
        $sql = "SELECT a.id as album_id, a.artist_id, a.name, c.name as genre_name, a.description, a.cover, a.cover_small, a.release_date , a.popularity FROM " . $this->table . " a INNER JOIN genres_albums b ON b.album_id = a.id
        INNER JOIN genres c ON c.id = b.genre_id ";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }

    public function want_specifique($target){
        $sql = "SELECT a.id as album_id, a.artist_id, a.name as album_name, c.name as genre_name, a.description, a.cover, a.cover_small, a.release_date , a.popularity FROM " . $this->table . " a INNER JOIN genres_albums b ON b.album_id = a.id
        INNER JOIN genres c ON c.id = b.genre_id WHERE a.name='".$target."' LIMIT 1";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }

    public function collection_of_target($target){
        $sql = "SELECT d.id as artiste_id, a.id as album_id, a.artist_id, a.name, c.name as genre_name, a.description, a.cover, a.cover_small, a.release_date , a.popularity FROM " . $this->table . " a INNER JOIN genres_albums b ON b.album_id = a.id
        INNER JOIN genres c ON c.id = b.genre_id INNER JOIN artists d ON a.artist_id = d.id WHERE d.id =".$target;

        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }

    public function list_of_track($target){
        $sql = "SELECT `id` as track_id, `album_id`, `name`, `track_no`, `duration`, `mp3` FROM " . $this->table2 . " WHERE `album_id` =".$target;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }
}
