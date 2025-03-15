<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia</title>
    <link rel="shortcut icon" href="../imgs/logo/logo_definitiva_simplificada.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/not.css">
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

        require_once("classesBanco" . DIRECTORY_SEPARATOR . "noticia.php");
        require_once("classesBanco" . DIRECTORY_SEPARATOR . "conexaoBd.php");

        //Instanciando as classes necessárias para a operação
        $dataBase = new DataBase();
        $db = $dataBase->connect();
        $noticia = new Noticia($db);

        $noticia->exibirNoticiaInfo();
    ?>

    <div class="content">
        <h1>Editar Notícia</h1>

        <div role="alert" id="mensagem-error" style="display: none;"> Mensagem </div>
        <div role="alert" id="mensagem-success" style="display: none;"> Mensagem </div>

        <form id="formAnime" enctype="multipart/form-data">
            <input type="text" id="manchete" name="manchete" placeholder="Manchete" value="<?php echo $noticia->getManchete();?>">
            <textarea id="url" name="url" placeholder="URL"><?php echo $noticia->getUrl();?></textarea>

            <div class="group">
                <div class="upload">
                    <input type="file" id="capaNoticia" accept="image/jpeg, image/png, image/bmp">

                    <?php 
                        $noticia_id = isset($_GET['id']) ? intval($_GET['id']) : null;
                        $blobUploadIcon = $noticia->pegaBlob($noticia_id);
                        $base64Blob = base64_encode($blobUploadIcon); // Converte o binário para base64
                        echo "<script> 
                                       var blobUploadIcon = 'data:image/png;base64," . $base64Blob . "';
                                       
                              </script>";
                    ?>

                    <img src="<?php echo $noticia->getCapa();?>" alt="upload" id="upload-icon">
                    <span id="legend" style="<?php if($noticia->getCapa()) echo 'display: none;'; else echo 'display: flex;';?>">Capa da Noticia</span>
                </div>

                <?php

                    // Supondo que o formato retornado seja algo como 'DD/MM/YYYY'
                    $dataPub = $noticia->getDataPub();

                    //Converte o formato da data para "YYYY-MM-DD"
                    $dataForm = DateTime::createFromFormat('d/m/Y', $dataPub)->format('Y-m-d');
                ?>

                <div class="subgroup">
                    <input type="date" id="dataPub" name="dataPub" placeholder="dataPub" value="<?php echo $dataForm?>">
                </div>
            </div>

            <div class="buttons">
                <button type="button" class="cad_noticia" id="edit_new">Editar Notícia</button>
                <button type="button" class="cad_noticia" id="delete_new">Deletar Noticia</button>
                
            </div>
        </form>

            
    </div>

        <!-- Modal para confirmar exclusão do anime -->
        <dialog class="popUp">
                    <p id="text-dialog">Tem certeza de que deseja excluir essa notícia?</p>
                    <div class="dialog-buttons">
                        <button id="confirm-dialog">Sim</button>
                        <button id="deny-dialog">Não</button>
                    </div>
        </dialog>



    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/scriptEditarNoticia.js"></script>
    <script src="../js/scriptDialogNot.js"></script> 
    
    
</body>
</html>
