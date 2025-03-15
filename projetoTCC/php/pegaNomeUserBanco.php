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

    $nome = $user->puxaNome($id);

    if($nome){
        $_SESSION['nome_usuario'] = $nome;
        echo json_encode(['status' => 'success', 'nome' => $nome]);
    }else{
        echo json_encode(['success' => false, 'message' => 'Não foi possível carregar o nome do banco!']);
    }
?>