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

    <style>
      /*editando a div .botoesDU*/
      .botoesDU{
        position: absolute;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.400);
        height: 50px;
        width: 100%;
        border-radius: 0px 0px 3px 3px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
      }
    
      /*Para o botão de editar*/
      button#animeEdit{
        background-color: transparent;
        border: none;
        border-radius: 5px;
        height: 30px !important;
        width: 30px !important;
        margin: 2px;  
      }

      button#animeEdit > img{
        width: 100%;
        height: 100%;
      }

      button#animeEdit:hover{
        cursor: pointer;   
      }

      button#animeEdit > img:active{
        opacity: 0.6;
      }

      button#animeEdit:focus{
        outline-style: none;
      }

      /*==================================================================*/

      /*Para o botão de excluir*/
      button#animeDelete{
        background-color: transparent;
        border: none;
        border-radius: 5px;
        height: 30px !important;
        width: 30px !important;
        margin: 2px;  
      }

      button#animeDelete > img{
        width: 100%;
        height: 100%;
      }

      button#animeDelete:hover{
        cursor: pointer;   
      }

      button#animeDelete > img:active{
        opacity: 0.6;
      }

      button#animeDelete:focus{
        outline-style: none;
      }

    </style>

    <title>Anime Edit</title>
  </head>
  <body>
    <?php 
    session_start(); // Certifique-se de que session_start() está aqui

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
          <div class="botoesDU">
            <!--<a href="deletaAnime.php?id=<?php //echo $id_anime;?>">-->
              <abbr title="Excluir anime?">  
                  <button id="animeDelete" style="display: flex;"><img src="../imgs/icons/lixeira_branca.png" alt="icone de excluir"></button>
              </abbr>

              <!-- Modal para confirmar exclusão do anime -->
              <dialog class="popUp">
                <p id="text-dialog">Tem certeza de que deseja excluir o anime?</p>
                <div class="dialog-buttons">
                  <button id="confirm-dialog">Sim</button>
                  <button id="deny-dialog">Não</button>
                </div>
              </dialog>

            <!--</a>-->
            <a href="telaEditarAnime.php?id=<?php echo $id_anime;?>">
              <abbr title="Editar anime?"><button id="animeEdit" style="display: flex;"><img src="../imgs/icons/edit_branco.png" alt="icone de editar"></button></abbr>
            </a>
          </div>
        </figure>

        <abbr title="Pontuação do público"><span id="notaGeral"><?php echo $users_anime->chamaMediaAval($id_anime);?>%</span></abbr>

        
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

    <!-- JavaScript (Opcional) -->
    <script src="../js/scriptDialogAni.js"></script>
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>