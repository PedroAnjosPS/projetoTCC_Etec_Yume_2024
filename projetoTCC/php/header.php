<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
</head>

  <style>
    :root{
      --cor0: #2A2D39;
      --cor1: #CDDCEE;
      --cor2: #8BBFFC;
      --cor3: #E1E1E1;
      --cor4: #EFEDED;
      --cor5: #E2E2E2;
    }

    .botoes > .dropdown > button > img{
      border-radius: 50%;
      height: 40px;
      width: 40px;
      margin-right: 15px;
    }

    .dropdown-toggle{
      color: black;
      font-weight: 600;
    }

    .dropdown-toggle:hover{
      background-color: var(--cor4);
      color: black;
    }

    .dropdown-toggle:focus{
      outline-style: none !important;
      border: none !important;
      box-shadow: none !important;
    }

    .dropdown-toggle::after {
      color: black; /*mudando a cor da setinha*/
    }

    .btn-secondary{
      background-color: white;
    }

    .dropdown-menu img{
      height: 20px;
      width: 20px;
    }
  </style>

<body>
  <header>
    <?php
      
      if(isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
      } else {
        //echo json_encode(['status' => 'error', 'message' => 'Usuário não está logado.']);
        $id = null;
      }
      if (isset($_SESSION['perfil'])) {
        $caminhoImagem = $_SESSION['perfil'] . '?t=' . time();
      } else {
        $caminhoImagem = '../imgs/icons/usuario-de-perfil.png'; // Caminho padrão para imagem de perfil
      }

      if(isset($_SESSION['nome_usuario'])){
        $nomeUsuario = $_SESSION['nome_usuario'];
      } else {
        $nomeUsuario = null;
      }

      //chamando os arquivos necessários para a operação
      require_once("classesBanco" . DIRECTORY_SEPARATOR . "anime.php");
      require_once("classesBanco" . DIRECTORY_SEPARATOR . "users_anime.php");
      require_once("classesBanco" . DIRECTORY_SEPARATOR . "conexaoBd.php");

      //Instanciando as classes necessárias para a operação
      $dataBase = new DataBase();
      $db = $dataBase->connect();
      $anime = new Anime($db);
      $users_anime = new UsersAnime($db);


      //retorna todos os nomes dos animes no banco
      $titulosAnimes = $anime->selectNomesAnimes();
      //var_dump($titulosAnimes);

      
    ?>

    <div class="logo">
      <a href="index.php"><img src="../imgs/logo/logo_definitiva.png" alt="Logo"></a>
    </div>

    <div class="search" id="search">
      <input type="search" id="in-search" name="in-search" autocomplete="off"> 
      <div class="suggestions"></div>
        <button type="button" id="btn-search"><img src="../imgs/icons/lupa.png" alt="lupa"></button>
    </div>

    <div class="botoes">
      <?php if($id): ?>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
              
              <?php if($id == null){ ?>

                  <img src="../imgs/icons/usuario-de-perfil.png" alt="imagem usuario">

              <?php } else{?>

                <img src="<?php echo htmlspecialchars($caminhoImagem); ?>" alt="imagem usuario">

              <?php }?>

              <?php echo htmlspecialchars($nomeUsuario); ?> 
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="editarPerfil.php"> <img src="../imgs/icons/editar_perfil.png" alt="editar perfil"> <span>Editar perfil</span></a>
                <a class="dropdown-item" href="listScreen.php"> <img src="../imgs/icons/listagem.png" alt="listagem"> <span>Lista de animes</span></a>
                <a class="dropdown-item" href="sair.php"> <img src="../imgs/icons/logout.png" alt="logout"> <span>Sair</span></a>
            </div>
        </div>
      <?php else: ?>
        <a href="telaLoginUsuario.php"><button type="button" class="btn btn-warning">Entrar</button></a>
        <a href="telaCadastroUsuario.php"><button type="button" class="btn btn-warning">Cadastrar</button></a>
      <?php endif; ?>
    </div>
  </header>

  <script>
    let sugestoes = <?php echo !empty($titulosAnimes) ? $titulosAnimes : '[]'; ?>;
    console.log(sugestoes);
  </script>
  <script src="../js/pesquisaAnimes.js"></script>
  
</body>
</html>
