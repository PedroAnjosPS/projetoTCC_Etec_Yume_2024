<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Anime</title>
    <link rel="shortcut icon" href="../imgs/logo/logo_definitiva_simplificada.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/ani.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        
        /*
            //Peguei esses estilos do consolo depois de ser criado o 'preview-image', aí adaptei no 'upload-icon'.
            element.style {
                max-width: 100%;
            }
            #preview-image.show {
                opacity: 0.6;
                transform: scale(1);
            }
            #preview-image {
                width: 120px;
                height: 150px;
                border-radius: 5px;
                opacity: 0;
                transition: opacity 0.8s ease-in-out, transform 0.5s ease-in-out;
                transform: scale(0.60);
            }
        */
        
        
            #upload-icon{
                width: 120px;
                height: 150px;
                border-radius: 5px;
                opacity: 0.6;
                transition: opacity 0.8s ease-in-out, transform 0.5s ease-in-out;
                transform: scale(1);
            }
        
    </style>

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

        //chamando os arquivos necessários para a operação
        require_once("classesBanco" . DIRECTORY_SEPARATOR . "anime.php");
        require_once("classesBanco" . DIRECTORY_SEPARATOR . "conexaoBd.php");

        //Instanciando as classes necessárias para a operação
        $dataBase = new DataBase();
        $db = $dataBase->connect();
        $anime = new Anime($db);

        $anime->exibirAnimeInfo();

        
    ?>

    <div class="content">
        <h1>Editar Anime</h1>

        <div role="alert" id="mensagem-error" style="display: none;"> Mensagem </div>
        <div role="alert" id="mensagem-success" style="display: none;"> Mensagem </div>

        <form id="formAnime" enctype="multipart/form-data">
            <input type="text" id="titulo" name="titulo" placeholder="Título" value="<?php echo $anime->getTitulo();?>">
            <textarea id="descricao" name="descricao" placeholder="Descrição"><?php echo $anime->getDescricao();?></textarea>

        <!-- Checkbox de gênero -->
        <fieldset>
            <h2>Escolha o gênero do anime:</h2>

            <div class="checkbox-container">
                <?php 
                    function checkboxGenAni(){
                        //Pegando o id do anime na URL
                        $anime_id = isset($_GET['id']) ? intval($_GET['id']) : null;

                        /*1- realizando a conexao com o banco de dados (local, usuario,senha,nomeBanco)*/
                        $cnn = mysqli_connect("localhost","root","","yume");

                        /*2- criando o comando sql para consulta dos registros */
                        $comandoSql = "SELECT genero_id, genero FROM genero";

                        /*3- executando o comando sql */
                        $resultado = mysqli_query($cnn,$comandoSql);
                    
                        /*4- Comando SQL para selecionar os generos que foram marcados no cadastro*/
                        $sqlGenSelec = "SELECT cod_genero FROM anime_genero
                                               WHERE cod_anime = $anime_id";

                        $resultGenSelec = mysqli_query($cnn, $sqlGenSelec); //executando a query

                        $genSelec = []; //array

                        while($registro = mysqli_fetch_assoc($resultGenSelec)){
                            $genSelec[] = $registro['cod_genero']; //armazena os ids dos generos selecionados no cadastro do anime
                        }


                        if($resultado){
                            while($dados = mysqli_fetch_assoc($resultado)){
                                $id = $dados["genero_id"];
                                $genero = $dados["genero"];
                                $selecionado = in_array($id, $genSelec) ? 'checked' : ''; /* Verifica se é o mesmo id do genero que foi marcado no cadastro do anime, se sim, recebe o valor 'checked' */
                                echo "<input type='checkbox' name='genero[]' id='$id' value='$id' $selecionado> <label for='$id'> $genero </label>";
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

                    <?php 
                        $anime_id = isset($_GET['id']) ? intval($_GET['id']) : null;
                        $blobUploadIcon = $anime->pegaBlob($anime_id);
                        $base64Blob = base64_encode($blobUploadIcon); // Converte o binário para base64
                        echo "<script> 
                                       var blobUploadIcon = 'data:image/png;base64," . $base64Blob . "';
                                       
                              </script>";
                    ?>

                    <img src="<?php echo $anime->getCapa() ? $anime->getCapa() : '../imgs/nuvem.png'; ?>" alt="upload" id="upload-icon">
                    <span id="legend" style="<?php if($anime->getCapa()) echo 'display: none;'; else echo 'display: flex;';?>">Capa do Anime</span>
                    <!--<img src="../imgs/nuvem.png" alt="upload" id="upload-icon">
                    <img src="?php echo $anime->getCapa();?" alt="upload" id="upload-icon" >
                    <span id="legend" style="display: none;">Capa do Anime</span>-->
                </div>

                <div class="subgroup">
                    <input type="text" id="qtdEpisodios" name="qtdEpisodios" placeholder="nº de episódios" value="<?php echo $anime->getQtEp();?>">
                    
                    <?php
                        // Supondo que o formato retornado seja algo como 'DD/MM/YYYY'
                        $dataLancamento = $anime->getDataLanc();

                        //Converte o formato da data para "YYYY-MM-DD"
                        $dataForm = DateTime::createFromFormat('d/m/Y', $dataLancamento)->format('Y-m-d');
                    ?>

                    <input type="date" id="data" name="data" placeholder="data" value="<?php echo $dataForm;?>">
                </div>
            </div>

            <button type="button" class="edit_anime" id="edit_anime">Editar Anime</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/scriptEditarAnime.js"></script>
</body>
</html>
