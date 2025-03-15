<?php
    session_start();

    if(isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
    } else {
        //echo json_encode(['status' => 'error', 'message' => 'Usuário não está logado.']);
        $id = null;
    }

    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'users.php');

    $database = new DataBase();
    $db = $database->connect();

    $user = new Users($db);

    $data = json_decode(file_get_contents('php://input'), true);
    $novoNome = $data['nome'];
    $_SESSION['nome_usuario'] = $novoNome;

    if($user->alteraNome($id, $novoNome)){
        echo json_encode(['status' => 'success', 'message' => 'Nome atualizado com sucesso!!']);
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Erro ao tentar atualizar o nome do usuário!!']);
    }
?>