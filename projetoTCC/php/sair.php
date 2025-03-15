<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
		
  </head>
  <body>

    <?php
      session_start();

      //código para tentar apagar as imagens relacionadas ao usuário
      $pathImg = "../imgs/";
      $commmon_word = "foto_de_perfil_";
      
      //Busca as imagens na pasta que tenham um nome em comum
      $img = glob($pathImg . "*" . $commmon_word . "*"); //O '*' antes e depois da palavra permite que o padrão pegue qualquer parte do nome antes ou depois da palavra.

      foreach($img as $image){
        if(is_file($image)){
          unlink($image); //apaga o arquivo
        }
      }

      //Código para apagar as imagens da pasta 'capas'
      $capa = glob("../imgs/capas/" . "*" . $commmon_word . "*"); //O '*' antes e depois da palavra permite que o padrão pegue qualquer parte do nome antes ou depois da palavra.

      foreach($capa as $capas){
        if(is_file($capas)){
          unlink($capas); //apaga o arquivo
        }
      }

      unset($_SESSION['perfil']);

      unset($_SESSION['nome_usuario']);
      unset($_SESSION['user_id']);
      

      header('location: index.php');
    ?>
  
 	
	 <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
  </body>
</html>



