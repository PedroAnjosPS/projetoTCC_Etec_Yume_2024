<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Notícia</title>
    <link rel="shortcut icon" href="../imgs/logo/logo_definitiva_simplificada.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/not.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <?php 
        session_start(); // Certifique-se de que session_start() está aqui

        require_once "headerAdm.php";
    ?>

    <div class="content">
        <h1>Cadastro de Notícia</h1>

        <div role="alert" id="mensagem-error" style="display: none;"> Mensagem </div>
        <div role="alert" id="mensagem-success" style="display: none;"> Mensagem </div>

        <form id="formAnime" enctype="multipart/form-data">
            <input type="text" id="manchete" name="manchete" placeholder="Manchete">
            <textarea id="url" name="url" placeholder="URL"></textarea>

            <div class="group">
                <div class="upload">
                    <input type="file" id="capaNoticia" accept="image/jpeg, image/png, image/bmp">
                    <img src="../imgs/icons/nuvem.png" alt="upload" id="upload-icon">
                    <span id="legend">Capa da Noticia</span>
                </div>

                <div class="subgroup">
                    <input type="date" id="dataPub" name="dataPub" placeholder="dataPub">
                </div>
            </div>

            <button type="button" class="cad_noticia" id="cad_noticia">Cadastrar Notícia</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/scriptCadNoticia.js"></script>
</body>
</html>
