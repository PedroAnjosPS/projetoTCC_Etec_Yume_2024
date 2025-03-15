<?php
    session_start();

    header('Content-Type: plain/text');

    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'users_anime.php');

    $database = new DataBase();
    $db = $database->connect();

    $users_anime = new UsersAnime($db);

    //Obtendo o id do anime na url
    $animeId = isset($_GET['id']) ? intval($_GET['id']) : null;

    //Obtem o id do usuario
    $userId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

    if($userId === null || $animeId === null){
        echo "Um dos dois ids (ou os dois) é(são) inválido(s)!";
        exit;
    }

    if($users_anime->removeAniLista($userId, $animeId)){
        echo "success";
    }else{
        echo "failed";
    }
?>