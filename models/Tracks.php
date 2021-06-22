<?php
class Tracks{
    // Connexion
    private $connexion;
    private $table = "tracks";
    public $id;
    public $album_id;
    public $name;
    public $track_no;
    public $duration;
    public $mp3;

    public function __construct($db){
        $this->connexion = $db;
    }

    public function list(){
        $sql = "SELECT `id` as id_track, `album_id` ,`name` ,`track_no` ,`duration` ,`mp3` FROM " . $this->table;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }
    public function want_specifique($target){
        $sql = "SELECT a.id as artist_id, a.name, a.description, a.bio, a.photo FROM " . $this->table . " a WHERE id =".$target." LIMIT 1";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }
}