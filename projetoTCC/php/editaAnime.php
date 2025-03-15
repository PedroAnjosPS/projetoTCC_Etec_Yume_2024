<?php
//ignora os notices e erros para não atrapalhar a resposta de retorno para o ajax
error_reporting(E_ALL & ~E_NOTICE); 

// Requerindo os arquivos necessários para cadastro do anime com o form
require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
require_once('classesBanco' . DIRECTORY_SEPARATOR . 'anime.php');
require_once('classesBanco' . DIRECTORY_SEPARATOR . 'anime_genero.php');

// Instanciando as classes:
$database = new DataBase();
$db = $database->connect(); // Nessa linha, a variável $db recebe o objeto de conexão da classe DataBase()

$anime = new Anime($db);

$anime_genero = new AnimeGenero($db);

//Pegando o id do anime na URL
$anime_id = isset($_GET['id']) ? intval($_GET['id']) : null;


// Definindo o tipo de conteúdo da resposta
header('Content-Type: text/plain');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os dados do formulário (se estiver tudo ok)
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $qtdEpisodios = isset($_POST['qtdEpisodios']) ? $_POST['qtdEpisodios'] : '';
    $data = isset($_POST['data']) ? $_POST['data'] : '';
    $genero = isset($_POST['genero']) && is_array($_POST['genero']) ? $_POST['genero'] : []; //verifica se o array foi recebido e se é, de fato um array
    

    //verificando se deu certo o array de genero:
    //var_dump($genero);

    // Verificação semelhante para pegar a imagem inserida (usando a superglobal $_FILES[])
    $capaAnime = isset($_FILES['capaAnime']) ? $_FILES['capaAnime'] : null;

    // Verifica se os dados estão 'vazios', caso sim, não é criado um registro no banco, só vai pro fim do código
    if (empty($titulo) || empty($descricao) || empty($qtdEpisodios) || empty($data) || !$capaAnime || $capaAnime['error'] != 0 || empty($genero)) {
        echo "Dados incompletos ou imagem não enviada";
        exit;
    }

    // Faz uma verificação se a imagem foi enviada de fato, se sim, armazena como binário
    if ($capaAnime && $capaAnime['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/bmp']; // array com os seguintes formatos de imagem

        if (!in_array($capaAnime['type'], $allowedTypes)) { // Verificando se a imagem NÃO compartilha de um formato em comum a esses definidos
            echo "Formato de imagem inválido";
            exit;
        }

        if ($capaAnime['size'] > 2 * 1024 * 1024) { // Verifica se a imagem ultrapassa 2MB
            echo "Imagem muito grande. Máximo de 2MB permitido";
            exit;
        }

        // Lê a imagem e armazena como binário
        $capaAnime = file_get_contents($capaAnime['tmp_name']);
    } else {
        $capaAnime = null;
    }

    // 'Seta' os atributos do anime
    $anime->setTitulo($titulo);
    $anime->setDescricao($descricao);
    $anime->setQtEp($qtdEpisodios);
    $anime->setDataLanc($data);
    $anime->setCapa($capaAnime);

    

    // Tenta editar o anime no Bd
    if ($anime->updateAnime($anime_id)) {
        //pegando o id do anime com uma função PDO que pega o último id inserido no banco
        

        if($anime_genero->updateAnimeGenero($anime_id, $genero)){
            echo "success";
        }else{
            echo "Falha ao atualizar os gêneros do anime";
        }
        
        
    } else {
        echo "Falha ao editar o anime";
    }

    

}
?>
