<?php
  require_once "users.php";

  session_start();

  global $cnn;

  // Captura o conteúdo JSON enviado pelo AJAX
  $json = file_get_contents('php://input');

  // Decodifica o JSON para um objeto PHP
  $dados = json_decode($json);

  // Acessa os valores de e-mail e senha
  $email = $dados->e;
  $senha = $dados->s;

  // Consulta SQL para verificar se o usuário existe
  $comandoSql = "SELECT * FROM users WHERE email='$email' AND senha='$senha'";
  $resultado = mysqli_query($cnn, $comandoSql);

  // Verifica se o usuário foi encontrado
  if (mysqli_num_rows($resultado) > 0) {
      $dados = mysqli_fetch_assoc($resultado);

      //pega o tipo do usuario:
      $isAdm = $dados["adm"];

      //pegando a imagem do banco
      $imagem = $dados['perfil']; 
      if($imagem != null){
        $nomeImg = uniqid('foto_de_perfil_', true) . '.jpeg';

        $pathImg = '../imgs/' . $nomeImg;

        file_put_contents($pathImg, $imagem);
      }else{
        $pathImg = null;
      }
      
      // Obtém o ID do usuário
      $id = $dados["user_id"];
      $nome = $dados["nome"];
      $status_conta = intval($dados["status_bd"]);

      if($status_conta === 0){
        echo json_encode(["status" => "desativado", "user_id" => $id]);
        exit;
      }
      
      //salvando na sessão
      $_SESSION['nome_usuario'] = $nome;
      $_SESSION['user_id'] = $id;
      $_SESSION['perfil'] = $pathImg;
      $_SESSION["isAdm"] = $isAdm;
      
      // Retorna uma resposta JSON com status de sucesso e o ID do usuário
      echo json_encode(["status" => "success"]);
  } else {
      // Retorna uma resposta JSON com status de falha caso o usuário não seja encontrado
      echo json_encode(["status" => "failed"]);
  }
?>
