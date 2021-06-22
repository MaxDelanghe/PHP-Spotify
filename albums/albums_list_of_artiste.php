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

    if(empty($_GET['target'])){
        echo json_encode(["message" => "Artist uknow in data base"]);
        die;
    }else{ 
        $target = $_GET['target'];
        //echo json_encode(["message" => "ok"]);
    }

    $database = new Database();
    $db = $database->getConnection();
    $albums = new Albums($db);
    $stmt = $albums->collection_of_target($target);

    if($stmt->rowCount() > 0){

        $tableauAlbums = [];
        $tableauAlbums['Albums'] = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $al = [
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
            $stmt2 = $albums->list_of_track($al['album_id']);
            if($stmt2->rowCount() > 0){
                $tableauTracks = [];
                $index = 0;
                while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
                    extract($row2);
                    $tr = [
                        "track_id" => $track_id,
                        "album_id" => $album_id,
                        "name" => $name,
                        "track_no" => $track_no,
                        "duration" => $duration,
                        "mp3" => $mp3
                    ];
                    $index++;
                    array_push($tableauTracks, $tr);
                }
            }else{echo json_encode(["message" => "merde"]);}
            if(!(empty($tableauTracks))){
                $al['nbr_of_tracks'] = $index;
                $al = array_merge($al, array("List_of_tracks"=> $tableauTracks));
                unset($tableauTracks);
            }
            $tableauAlbums['Albums'][] = $al;
        }
        http_response_code(200);

        echo json_encode($tableauAlbums);
    }

}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}