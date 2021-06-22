<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    include_once '../config/Database.php';
    include_once '../models/Albums.php';

    $database = new Database();
    $db = $database->getConnection();

    $albums = new Albums($db);
    $stmt = $albums->main();

    if($stmt->rowCount() > 0){

        $tableauAlbums = [];
        $tableauAlbums['Albums'] = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $al = [
                "album_id" => $album_id,
                "name" => $name,
                "genre_name" => $genre_name,
                "artist_id" => $artist_id,
                "description" => $description,
                "cover" => $cover,
                "cover_small" => $cover_small,
                "release_date" => $release_date,
                "popularity" => $popularity
            ];
            $tableauAlbums['Albums'][] = $al;
        }
        http_response_code(200);
        echo json_encode($tableauAlbums);
    }

}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
