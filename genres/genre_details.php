<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    include_once '../config/Database.php';
    include_once '../models/Genres.php';

    if(empty($_GET['target'])){
        echo json_encode(["message" => "Genre uknow in data base"]);
        die;
    }else{ 
        $target = $_GET['target'];
    }

    $database = new Database();
    $db = $database->getConnection();

    $genre = new Genre($db);
    $stmt = $genre->album_of_this_genre($target);

    if($stmt->rowCount() > 0){

        $tableauGenre = [];
        $tableauGenre['Genre'] = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $al = [
                "name" => $name,
                "album_id" => $album_id,
            ];
            $tableauGenre['Genre'][] = $al;
        }
        http_response_code(200);
        echo json_encode($tableauGenre);
    }

}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
