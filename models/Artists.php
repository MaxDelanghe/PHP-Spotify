<?php
class Artistes{
    // Connexion
    private $connexion;
    private $table = "artists";
    private $table2 = "albums";


    // object properties	
    public $id;
    public $name;
    public $description;
    public $bio;
    public $photo;

    // Constructeur avec $db pour la connexion à la base de données
    public function __construct($db){
        $this->connexion = $db;
    }

     // Lecture des artistes
    public function list(){
        // On écrit la requête
        $sql = "SELECT a.id as artist_id, a.name, a.description, a.bio, a.photo FROM " . $this->table . " a ";
        // On prépare la requête
        $query = $this->connexion->prepare($sql);
        // On exécute la requête
        $query->execute();
        // On retourne le résultat
        return $query;
    }
    public function want_specifique($target){
        $sql = "SELECT a.id as artist_id, a.name, a.description, a.bio, a.photo FROM " . $this->table . " a WHERE id =".$target." LIMIT 1";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }
    public function list_of_album($target){

        $sql = "SELECT d.id as artiste_id, a.id as album_id, a.name, c.name as genre_name, a.description, a.cover, a.cover_small, a.release_date , a.popularity FROM " . $this->table2 . " a INNER JOIN genres_albums b ON b.album_id = a.id
        INNER JOIN genres c ON c.id = b.genre_id INNER JOIN artists d ON a.artist_id = d.id WHERE d.id =".$target;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }
}