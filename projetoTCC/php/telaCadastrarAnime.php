<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Animes</title>
    <link rel="shortcut icon" href="../imgs/logo/logo_definitiva_simplificada.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/ani.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <?php 
        session_start(); // Certifique-se de que session_start() está aqui

        require_once "headerAdm.php";
    ?>

    <div class="content">
        <h1>Cadastro de Anime</h1>

        <div role="alert" id="mensagem-error" style="display: none;"> Mensagem </div>
        <div role="alert" id="mensagem-success" style="display: none;"> Mensagem </div>

        <form id="formAnime" enctype="multipart/form-data">
            <input type="text" id="titulo" name="titulo" placeholder="Título">
            <textarea id="descricao" name="descricao" placeholder="Descrição"></textarea>

        <!-- Checkbox de gênero -->
        <fieldset>
            <h2>Escolha o gênero do anime:</h2>

            <div class="checkbox-container">
                <?php 
                    function checkboxGenAni(){
                        /*1- realizando a conexao com o banco de dados (local, usuario,senha,nomeBanco)*/
                        $cnn = mysqli_connect("localhost","root","","yume");

                        /*2- criando o comando sql para consulta dos registros */
                        $comandoSql = "SELECT genero_id, genero FROM genero";

                        /*3- executando o comando sql */
                        $resultado = mysqli_query($cnn,$comandoSql);
                    

                        if($resultado){
                            while($dados = mysqli_fetch_assoc($resultado)){
                                $id = $dados["genero_id"];
                                $genero = $dados["genero"];

                                echo "<input type='checkbox' name='genero[]' id='$id' value='$id'> <label for='$id'> $genero </label>";
                            }
                        }else{
                            echo "Erro ao tentar puxar informação do banco!!!";
                        }

                    }

                    checkboxGenAni();
                ?>
            </div>
        </fieldset>
    
            

            

            <div class="group">
                <div class="upload">
                    <input type="file" id="capaAnime" accept="image/jpeg, image/png, image/bmp">
                    <img src="../imgs/nuvem.png" alt="upload" id="upload-icon">
                    <span id="legend">Capa do Anime</span>
                </div>

                <div class="subgroup">
                    <input type="text" id="qtdEpisodios" name="qtdEpisodios" placeholder="nº de episódios">
                    <input type="date" id="data" name="data" placeholder="data">
                </div>
            </div>

            <button type="button" class="cad_anime" id="cad_anime">Cadastrar Anime</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/scriptCadAnime.js"></script>
</body>
</html>
