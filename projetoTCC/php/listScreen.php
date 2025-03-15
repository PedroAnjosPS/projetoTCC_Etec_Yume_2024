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

    <title>ResultSearch</title>
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

      //require_once "header.php"; 

      //chamando os arquivos necessários para a operação
      require_once("classesBanco" . DIRECTORY_SEPARATOR . "anime.php");
      require_once("classesBanco" . DIRECTORY_SEPARATOR . "users_anime.php");
      require_once("classesBanco" . DIRECTORY_SEPARATOR . "conexaoBd.php");

      //Instanciando as classes necessárias para a operação
      $dataBase = new DataBase();
      $db = $dataBase->connect();
      $anime = new Anime($db);
      $users_anime = new UsersAnime($db);

      $codsAnime = $users_anime->retornaAnimesSalvos($id);
      
      
    ?>

    <main class="resultSearch">

            

        <!--<a href="animeInfo.php" style="text-decoration: none; color: black;">
          <div class="resultAnime">
            <img src="../imgs/poster1.png" alt="img teste">
          
            <p class="animeDetails">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore repellendus Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus illum ex nam vel consequatur</p>
          
            <div class="details no-link">
              <div class="nota no-link">
                  <abbr title="Pontuação do público"><span id="notaGeral">90%</span></abbr>
              </div>
              <button class="excluir no-link"><img src="../imgs/icons/lixeira.png" alt="salvar"></button>
            </div>
          </div>
        </a>-->



        <?php 
              if(!$anime->exibirAnimesSalvos($codsAnime)){
                echo "<p style='text-align: center; margin: 30px auto; font-size: 1.2em; font-weight: 600;'>
                        Não há animes salvos
                      </p>";
              }
        ?>


    </main>
    
    <!-- JavaScript (Opcional) -->
     <script>
        document.addEventListener('DOMContentLoaded', function(){
          var semLink = document.querySelectorAll('.no-link'); //Pega todos os elementos que tem a classe no-link (vira um array)

          semLink.forEach(function(div) { //Adiciona um evento de clique para cada elemento da classe no-link
            div.addEventListener('click', function(event){
              event.stopPropagation(); //Impede que o evento de clique se espalhe para elementos ancestrais (elementos pais) 
              event.preventDefault(); // Previne a ação padrão do link
            });
          });
        })
     </script>
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/removeAnimeLista.js"></script>
  </body>
</html>