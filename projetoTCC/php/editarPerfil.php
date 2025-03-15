<!DOCTYPE html>
<html lang="pt-br">
<head>
  <!-- Meta tags Obrigatórias -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="shortcut icon" href="../imgs/logo/logo_definitiva_simplificada.png" type="image/x-icon">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/estilo.css">
  <link rel="stylesheet" href="../css/editarPerfil.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <title>Editar Perfil</title>
  
</head>

<body>
  <?php
    session_start();

    if(isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
      } else {
        //echo json_encode(['status' => 'error', 'message' => 'Usuário não está logado.']);
        $id = null;
      }
      if (isset($_SESSION['perfil'])) {
        $caminhoImagem = $_SESSION['perfil'];
      } else {
        $caminhoImagem = '../imgs/icons/usuario-de-perfil.png'; // Caminho padrão para imagem de perfil
      }

      if(isset($_SESSION['nome_usuario'])){
        $nomeUsuario = $_SESSION['nome_usuario'];
      } else {
        $nomeUsuario = null;
      }

    require_once "header.php"; 


    echo "<script> var user_id = $id </script>";
    
  ?>

  <main>
        <div role="alert" id="mensagem-error" style="display: none;"> <span>Mensagem</span> </div>
        <div role="alert" id="mensagem-success" style="display: none;"> <span>Mensagem</span> </div>

        <div class="upload">
          <img src="<?php echo $caminhoImagem; ?>" alt="upload" id="upload-icon" style="<?php if($caminhoImagem != '../imgs/icons/usuario-de-perfil.png'){
              echo "opacity: 1;";
          }?>">
        </div>

        <div class="botao-upload">
          <input type="file" id="edit-foto" name="edit-foto" accept="image/jpeg, image/png, image/bmp">
          <label for="edit-foto">Escolher foto <img src="../imgs/icons/download.png" alt="botão de download"></label>
        </div>

        <div class="inputArea">
          <input type="text" id="nome" name="nome" pattern="[A-Za-z]{1,}-[0-9]{4}" title="Digite pelo menos 3 letras" required>
          <label for="nome" class="labelline">Nome</label>
        </div>

        <div class="exc_des_conta">
          <p id="desativa_conta">desativar conta</p>
          <p id="deleta_conta">excluir conta</p>
        </div>
  </main>

  <!-- Modal para confirmar exclusão do anime -->
  <dialog class="popUp" id="dialog">
                    <p id="text-dialog">Tem certeza de que deseja desativar sua conta?</p>
                    <div class="dialog-buttons" id="dialog-buttons">
                        <button id="confirm-dialog">Sim</button>
                        <button id="deny-dialog">Não</button>
                    </div>
  </dialog>

  <!-- Modal para confirmar exclusão do anime -->
  <dialog class="popUp" id="dialog1" style="margin: auto !important; border-radius: 5px; padding: 10px;">
                    <p id="text-dialog1">Tem certeza de que deseja excluir sua conta?</p>
                    <div class="dialog-buttons" id="dialog-buttons1">
                        <button id="confirm-dialog1">Sim</button>
                        <button id="deny-dialog1">Não</button>
                    </div>
  </dialog>

  <!-- JavaScript (Opcional) -->
    <script src="../js/editarPerfil.js"></script>

  <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="../js/scriptDialogUser.js"></script>
</body>
</html>
