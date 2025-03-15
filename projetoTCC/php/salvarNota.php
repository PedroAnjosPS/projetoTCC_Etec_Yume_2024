<?php 

    header('Content-Type: application/json'); //definindo o tipo de conteudo de resposta ao AJAX

    session_start();

    $userId = $_SESSION['user_id']; //pegando o id do usuário na sessão
    $animeId = $_GET['id']; //pegando o id do anime na URL

    
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'users_anime.php');

    //Instanciando as classes necessárias
    $database = new DataBase();
    $db = $database->connect();

    $users_anime = new UsersAnime($db);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $notaAnime = isset($_POST['nota']) ? (int)$_POST['nota'] : 0;

        if($users_anime->salvarNota($userId, $animeId, $notaAnime)){
            echo json_encode(['success' => true, 'message' => 'Nota do anime salva com sucesso!']);
        }else{
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar a nota do anime!']);
        }
    }

?>