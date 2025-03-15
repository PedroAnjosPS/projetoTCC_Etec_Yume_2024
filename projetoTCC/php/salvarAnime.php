<?php 
    session_start();

    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'users_anime.php');

    $database = new DataBase();
    $db = $database->connect();

    $users_anime = new UsersAnime($db);

    //Obtendo o id do anime na url
    $animeId = $_GET['id'];
    //retorna um valor booleano (lógico) depois da verificação se a var. salvo é true ou false
    $salvo = $_GET['salvo'] === 'true';

    //Obtem o id do usuario
    $userId = $_SESSION['user_id'];

    if($salvo){
        //Verifica se deu certo a execução do método e retorna uma resposta em JSON
        if($users_anime->salvarAnime($userId, $animeId)){
            echo json_encode(['status' => 'success']);
        }else{
            echo json_encode(['status' => 'error']);
        }
    }else{
        //Verifica se deu certo a execução do método e retorna uma resposta em JSON
        if($users_anime->alterarSalvoAnime($userId, $animeId)){
            echo json_encode(['status' => 'success']);
        }else{
            echo json_encode(['status' => 'error']);
        }
    }
?>