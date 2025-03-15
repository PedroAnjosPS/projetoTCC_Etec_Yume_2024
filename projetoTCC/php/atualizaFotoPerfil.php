<?php 
    session_start();

    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'users.php');
    //require_once 'users.php';

    $database = new DataBase();
    $db = $database->connect();

    $user = new Users($db);

    // Definindo o tipo de conteúdo da resposta
    header('Content-Type: text/plain');
    
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])){
        $imagem = $_FILES['imagem'];

        //Verificando se há erros no arquivo
        if($imagem['error'] === UPLOAD_ERR_OK){
            $nomeArquivoTemp = $imagem['tmp_name'];
            $tipoArquivo = $imagem['type'];
            $tamanhoArquivo = $imagem['size'];

            $formatosValidos = ['image/jpeg', 'image/png', 'image/bmp'];
            $tamanhoMaximo = 2 * 1024 * 1024; // 2MB

            if (!in_array($tipoArquivo, $formatosValidos)) {
                echo json_encode(['status' => 'error', 'message' => 'Formato de imagem inválido!!']);
                exit;
            }
    
            if ($tamanhoArquivo > $tamanhoMaximo) {
                echo json_encode(['status' => 'error', 'message' => 'Imagem muito grande. Máximo de 2MB!!']);
                exit;
            }

            //Convertendo a imagem pra binario pra salvar no banco
            $imgBinaria = file_get_contents($nomeArquivoTemp);

            //Pegando o id do usuario na sessão
            if(isset($_SESSION['user_id'])) {
                $id = $_SESSION['user_id'];
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Usuário não está logado.']);
                exit;
            }

            $user->setPerfil($imgBinaria);

            if($user->atualizaFoto($id, $imgBinaria)){
                echo json_encode(['status' => 'success', 'message' => 'Imagem atualizada com sucesso!!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erro ao tentar atualizar a imagem no banco de dados!!']);
            }

        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao fazer upload da imagem.']);
        }
    }
?>