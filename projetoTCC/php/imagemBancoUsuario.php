<?php 
    session_start();

    header('Content-Type: application/json'); // Define o tipo de conteúdo como JSON

    if(isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
    } else {
        //echo json_encode(['status' => 'error', 'message' => 'Usuário não está logado.']);
        $id = null;
    }

    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
    require_once('classesBanco' . DIRECTORY_SEPARATOR . 'users.php');
    //require_once 'users.php';

    $database = new DataBase();
    $db = $database->connect();

    $user = new Users($db);

    $imagem = $user->puxaImg($id);

    /*if($imagem){
        header("Content-Type: image/jpeg");
        echo $imagem;
        
        $caminho_imagem = '../imgs/fotoperfil.jpg'; //define o caminho da imagem
        file_put_contents($caminho_imagem, $imagem); //faz com o arquivo da imagem seja salvo na pasta desejada

        $_SESSION['perfil'] = $caminho_imagem; //salva na sessão
        
    }*/

    if($imagem){
        //adciona um nome pra a imagem
        $nomeImg = uniqid('foto_de_perfil_', true) . '.jpeg';

        //caminho aonde será salvo a imagem que será decodificada 
        $pathImg = '../imgs/' . $nomeImg;

        //decodificando o arquivo binário que foi pego do banco e salvando na pasta imgs
        file_put_contents($pathImg, $imagem);

        //Ignora esses códigos comentados
        //echo 'Imagem salva com sucesso em: ' . $pathImg;

        //echo '<img src="'. $pathImg .'" alt="Imagem do Banco">';

        //salva a imagem na sessao 
        $_SESSION['perfil'] = $pathImg;

        //retorna uma reposta de sucesso via em JSON para o JavaScript
        echo json_encode(['success' => true, 'caminhoImagem' => $pathImg]);
    }else{
        //ignora isso também |
                          // |
                          // V
        //echo 'Não foi possível carregar a imagem do banco!!';

        //retorna uma reposta de erro via em JSON para o JavaScript
        echo json_encode(['success' => false, 'message' => 'Não foi possível carregar a imagem do banco!']);
    }
?>