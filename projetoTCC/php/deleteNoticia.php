<?php
    /// Exibe erros na tela para depuração
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Definindo o tipo de conteúdo da resposta
    header('Content-Type: text/plain');
    //header('Content-Type: application/json');

    // Requerindo os arquivos necessários para cadastro do anime com o form
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'noticia.php');

    // Instanciando as classes:
    $database = new DataBase();
    $db = $database->connect(); // Nessa linha, a variável $db recebe o objeto de conexão da classe DataBase()

    $noticia = new Noticia($db);

    //Pegando o id do anime na URL
    $noticia_id = isset($_GET['id']) ? intval($_GET['id']) : null;


    if($noticia_id === null){
        //echo json_encode(['status' => 'error', 'message' => 'ID do anime inválido!']);
        echo "ID da noticia inválido!";
        exit;
    }

    //Tenta excluir o anime
    if($noticia->deleteNoticia($noticia_id)){
        //echo json_encode(['status' => 'success']);
        echo "success";
    }else{
        //echo json_encode(['status' => 'error']);
        echo "error";
    }


?>