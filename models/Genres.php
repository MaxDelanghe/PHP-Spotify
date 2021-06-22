<?php
class Genre{
    private $connexion;
    private $table = "genres";

    public $id;
    public $name;

    public function __construct($db){
        $this->connexion = $db;
    }

    public function list(){
        $sql = "SELECT a.id as genre_id, a.name as name_genre FROM " . $this->table . " a ";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }
    public function album_of_this_genre($target){
        $sql = "SELECT `name`,`id`as album_id FROM `albums` INNER JOIN `genres_albums` ON `id` = `album_id` WHERE `genre_id` =".$target;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }
}