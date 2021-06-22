<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    include_once '../config/Database.php';
    include_once '../models/Artists.php';

    if(empty($_GET['target'])){
        echo json_encode(["message" => "Artist uknow in data base"]);
        die;
    }else{ 
        $target = $_GET['target'];
        //echo json_encode(["message" => "ok"]);
    }

    $database = new Database();
    $db = $database->getConnection();
    $artiste = new Artistes($db);
    $stmt = $artiste->want_specifique($target);

    if($stmt->rowCount() > 0){

        $tableauArtistes = [];
        $tableauArtistes['artistes'] = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $art = [
                "artist_id" => $artist_id,
                "name" => $name,
                "description" => $description,
                "bio" => $bio,
                "photo" => $photo
            ];

            $stmt2 = $artiste->list_of_album($art['artist_id']);

            if($stmt2->rowCount() > 0){
                $tableauAlbums = [];
                $index = 0;
                while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
                    extract($row2);
                    $tr = [
                        "artiste_id" => $artiste_id,
                        "album_id" => $album_id,
                        "name" => $name,
                        "genre_name" => $genre_name,
                        "description" => $description,
                        "cover" => $cover,
                        "cover_small" => $cover_small,
                        "release_date" => $release_date,
                        "popularity" => $popularity
                    ];
                    $index++;
                    array_push($tableauAlbums, $tr);
                }
            }else{echo json_encode(["message" => "merde"]);}
            if(!(empty($tableauAlbums))){
                $art['nbr_of_album'] = $index;
                $art = array_merge($art, array("list_of_album"=> $tableauAlbums));
                unset($tableauAlbums);
            }
            $tableauArtistes['artistes'][] = $art;
        }
        http_response_code(200);
        echo json_encode($tableauArtistes);
    }

}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}