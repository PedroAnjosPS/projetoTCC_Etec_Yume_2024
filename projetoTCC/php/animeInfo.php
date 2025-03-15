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
    <link rel="stylesheet" href="../css/estilo2.css">
    <link rel="stylesheet" href="../css/titulosRelacionados.css">
    <link rel="stylesheet" href="../css/sistemaEstrela.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    

    <title>Anime Info</title>
  </head>
  <body>
  <?php 
    session_start(); // Certifique-se de que session_start() está aqui

    //$adm = $_SESSION['isAdm']; //armazenando o dado para ver se o usuário é adm ou não

    if(isset($_SESSION['user_id'])){
      
      $id = $_SESSION['user_id'];

      if($_SESSION['isAdm'] && $_SESSION['isAdm'] === '1'){
          require_once "headerAdm.php";
      }else{
          require_once "header.php";
      }

      $adm = $_SESSION['isAdm']; //armazenando o dado para ver se o usuário é adm ou não

      
    }else{
      $id = null;
      $adm = null;
      require_once "header.php";
    }

    if (isset($_SESSION['nome_usuario'])) {
        $nomeUsuario = $_SESSION['nome_usuario'];
    } else {
        $nomeUsuario = null;
    }
    
    //chamando os arquivos necessários para a operação
    require_once("classesBanco" . DIRECTORY_SEPARATOR . "anime.php");
    require_once("classesBanco" . DIRECTORY_SEPARATOR . "anime_genero.php");
    require_once("classesBanco" . DIRECTORY_SEPARATOR . "conexaoBd.php");
    require_once("classesBanco" . DIRECTORY_SEPARATOR . "users_anime.php");

    //Instanciando as classes necessárias para a operação
    $dataBase = new DataBase();
    $db = $dataBase->connect();
    $anime = new Anime($db);
    $anime_genero = new AnimeGenero($db);
    $users_anime = new UsersAnime($db);

    $anime->exibirAnimeInfo();

    //Pegando o id do anime na URL
    $id_anime = isset($_GET['id']) ? $_GET['id'] : null;
    
    ?>

    
    
    <main class="animeContent">
      <aside class="animeFigures">
        <figure>
          <img src="<?php echo $anime->getCapa();?>" alt="poster anime">

          <!-- Se o usuário estiver logado, ele poderá salvar o anime -->

          <?php 
              if($id){
          ?>

          <div class="salva-animes">            
            <abbr title="Salvar anime?" id="texto-abbr">
              <button id="animeSave">
                <i class="far fa-bookmark"></i>
              </button>
            </abbr>
          </div>

          <?php
              }
          ?>

        </figure>

        <abbr title="Pontuação do público"><span id="notaGeral"><?php echo $users_anime->chamaMediaAval($id_anime);?>%</span></abbr>

        <!-- Se o usuário estiver logado, ele poderá avaliar o anime -->

        <?php 
              if($id){
        ?>

        <div class="animeScore">

          <?php
            $notaUsuario = $users_anime->pegarNota($id, $id_anime);

            //restorna a nota salva para o arquivo JS
            echo "<script> var notaSalva = " . ($notaUsuario ? $notaUsuario : 0) . "; </script>"; 
          ?>


          <ul class="avaliacao">
            <li class="star-icon" data-avaliacao="1"></li> <!-- index = 0 -->
            <li class="star-icon" data-avaliacao="2"></li> <!-- index = 1 -->
            <li class="star-icon" data-avaliacao="3"></li> <!-- index = 2 -->
            <li class="star-icon" data-avaliacao="4"></li> <!-- index = 3 -->
            <li class="star-icon" data-avaliacao="5"></li> <!-- index = 4 -->
          </ul>

          <div role="alert" id="mensagem-success" style="display: none;"> Mensagem </div>
          <div role="alert" id="mensagem-error" style="display: none;"> Mensagem </div>

          <button name="nota" id="nota">Enviar nota</button>

          
        </div>

          <div role="alert" id="mensagem-success" style="display: none;"> Mensagem </div>
          <div role="alert" id="mensagem-error" style="display: none;"> Mensagem </div>

        <?php
              }
        ?>
      </aside>


      <article class="animeInfo">
          <div class="info0">
            <div class="info1">
              <h1 id="animeTitle"><?php echo $anime->getTitulo();?></h1>
              <div class="info2">
                <p style="text-indent: 1em;">Data de lançamento: <?php echo $anime->getDataLanc();?></p>
                
                <p style="padding-right: 1em;"><?php echo $anime->getQtEp();?> episódios</p>
              </div>
              <p style="height: auto;" id="genero_anime">Gênero(s): <?php $anime_genero->selectGenAnime(); ?>.</p>
            </div>
            
            <p id="animeSinopse"><?php echo $anime->getDescricao();?></p>
          </div>

          <div id="animeExtras">
            <h1 id="extraInformations">Títulos Semelhantes</h1>

            <div class="titulosRelacionados">

              <?php $anime->exibirTitulosSemel($id_anime, $adm);?>

            </div>

          </div>
      </article>
    </main>

    

    
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <!-- JavaScript (Opcional) -->
    <script src="../js/sistemaEstrela.js"></script>
    <script src="../js/salvarAnime.js"></script>
  </body>
</html>