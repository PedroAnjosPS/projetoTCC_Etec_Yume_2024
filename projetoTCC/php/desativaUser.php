<?php
    session_start();

    /// Exibe erros na tela para depuração
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Definindo o tipo de conteúdo da resposta
    header('Content-Type: text/plain');
    //header('Content-Type: application/json');

    // Requerindo os arquivos necessários para cadastro do anime com o form
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'users.php');

    // Instanciando as classes:
    $database = new DataBase();
    $db = $database->connect(); // Nessa linha, a variável $db recebe o objeto de conexão da classe DataBase()

    $user = new Users($db);

    //Pegando o id do anime na URL
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;


    if($user_id === null){
        //echo json_encode(['status' => 'error', 'message' => 'ID do anime inválido!']);
        echo "ID do usuário inválido!";
        exit;
    }

    //Tenta excluir o anime
    if($user->desativaUser($user_id)){
        //echo json_encode(['status' => 'success']);
        echo "success";
    }else{
        //echo json_encode(['status' => 'error']);
        echo "error";
    }


?>