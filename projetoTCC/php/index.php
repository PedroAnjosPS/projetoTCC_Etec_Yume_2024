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
  <link rel="stylesheet" href="../css/carrossel.css">

  <title>Página Principal</title>
  
  <script src="../js/carrosselNoticia.js" defer></script>
  <script src="../js/carrosselAnime.js" defer></script>
</head>
<body>
  <?php
    session_start();

    $adm = $_SESSION['isAdm']; //armazenando o dado para ver se o usuário é adm ou não

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

    //require_once "header.php"; 

    //chamando os arquivos necessários para a operação
    require_once("classesBanco" . DIRECTORY_SEPARATOR . "anime.php");
    require_once("classesBanco" . DIRECTORY_SEPARATOR . "noticia.php");
    require_once("classesBanco" . DIRECTORY_SEPARATOR . "conexaoBd.php");

    //Instanciando as classes necessárias para a operação
    $dataBase = new DataBase();
    $db = $dataBase->connect();
    $anime = new Anime($db);
    $noticia = new Noticia($db);
  ?>

  <main class="index">
    <div class="carousel-wrapper">
      <button class="prev"></button>


      <div class="carrosselAnimes">

        <?php
          $anime->exibirAnimeIndex($adm);
        ?>
        
        <!--<a href="animeInfo.php" style="text-decoration: none;">
          <div class="previewAnime">
            <img src="../imgs/poster1.png" alt="poster teste">
            <h1>Boku no Hero</h1>
          </div>
        </a>
        -->
      </div>
      <button class="next"></button>
    </div>

    
      <div class="carousel-wrapper1">
        <button class="prevNot"></button>
        <div class="carrosselNoticias">

        <?php
          $noticia->exibirNoticiaIndex($adm);
        ?>
          <!-- <div class="previewNoticia">
            <img src="../imgs/poster1.png" alt="poster teste">
            <h1>Noticia 1</h1>
          </div>
          <div class="previewNoticia">
            <img src="../imgs/poster1.png" alt="poster teste">
            <h1>Noticia 2</h1>
          </div>
          <div class="previewNoticia">
            <img src="../imgs/poster1.png" alt="poster teste">
            <h1>Noticia 3</h1>
          </div>
          <div class="previewNoticia">
            <img src="../imgs/poster1.png" alt="poster teste">
            <h1>Noticia 4</h1>
          </div>
          <div class="previewNoticia">
            <img src="../imgs/poster1.png" alt="poster teste">
            <h1>Noticia 5</h1>
          </div>
          <div class="previewNoticia">
            <img src="../imgs/poster1.png" alt="poster teste">
            <h1>Noticia 6</h1>
          </div>
          <div class="previewNoticia">
            <img src="../imgs/poster1.png" alt="poster teste">
            <h1>Noticia 7</h1>
          </div> -->
        </div>
        <button class="nextNot"></button>
      </div>
  </main>

  <!-- JavaScript (Opcional) -->
  <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
