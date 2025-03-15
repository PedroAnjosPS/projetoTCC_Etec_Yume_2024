<?php
    session_start();

    /// Exibe erros na tela para depuração
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Definindo o tipo de conteúdo da resposta
    //header('Content-Type: text/plain');
    header('Content-Type: application/json');

    // Requerindo os arquivos necessários para cadastro do anime com o form
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'users.php');

    // Instanciando as classes:
    $database = new DataBase();
    $db = $database->connect(); // Nessa linha, a variável $db recebe o objeto de conexão da classe DataBase()

    $user = new Users($db);

    //Pegando o id do anime na URL
    $user_id = isset($_GET['id']) ? intval($_GET['id']) : null;


    if($user_id === null){
        echo json_encode(['status' => 'error', 'message' => 'ID do usuário inválido!']);
        //echo "ID do usuário inválido!";
        exit;
    }

    //Tenta reativar a conta do usuário
    if($user->reativaUser($user_id)){
        $registro = $user->reativaUser($user_id);

        if($registro){
            $id_user = $registro['user_id'];
            $nome_user = $registro['nome'];
            //pega o tipo do usuario:
            $isAdm = $registro["adm"];

            //pegando a imagem do banco
            $imagem = $registro['perfil']; 
            if($imagem != null){
                $nomeImg = uniqid('foto_de_perfil_', true) . '.jpeg';

                $pathImg = '../imgs/' . $nomeImg;

                file_put_contents($pathImg, $imagem);
            }else{
                $pathImg = null;
            }

            //armazenando os dados do usuario na sessao
            $_SESSION['nome_usuario'] = $nome_user;
            $_SESSION['user_id'] = $id_user;
            $_SESSION['perfil'] = $pathImg;
            $_SESSION["isAdm"] = $isAdm;

            echo json_encode(['status' => 'success']);
            //echo "success";
        }else{
            echo json_encode(['status' => 'error']);
        }
        
    }else{
        echo json_encode(['status' => 'error']);
        //echo "error";
    }


?>