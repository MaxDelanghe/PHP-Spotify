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

    $database = new Database();
    $db = $database->getConnection();

    $genre = new Genre($db);
    $stmt = $genre->list();

    if($stmt->rowCount() > 0){

        $tableauGenre = [];
        $tableauGenre['Genre'] = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $al = [
                "genre_id" => $genre_id,
                "name_genre" => $name_genre,
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
