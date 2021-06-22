<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../models/Artists.php';

    $database = new Database();
    $db = $database->getConnection();
    $artiste = new Artistes($db);
    $stmt = $artiste->list();

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

            $tableauArtistes['artistes'][] = $art;
        }
        http_response_code(200);
        echo json_encode($tableauArtistes);
    }

}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}