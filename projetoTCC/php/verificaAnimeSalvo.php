<?php 
    session_start();

    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'users_anime.php');

    $database = new DataBase();
    $db = $database->connect();

    $users_anime = new UsersAnime($db);

    //Obtendo o id do anime na url
    $animeId = $_GET['id'];

    //Obtem o id do usuario
    $userId = $_SESSION['user_id'];

    //Verifica se o anime está salvo
    $isSalvo = $users_anime->isAnimeSalvo($userId, $animeId);

    //Retorna o resultado em JSON
    echo json_encode(['salvo' => $isSalvo]);
?>