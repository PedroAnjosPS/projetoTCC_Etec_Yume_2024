<?php
// Requerendo os arquivos necessários para cadastro do noticia com o form
require_once('classesBanco' . DIRECTORY_SEPARATOR . 'conexaoBd.php');
require_once('classesBanco' . DIRECTORY_SEPARATOR . 'noticia.php');

// Instanciando as classes:
$database = new DataBase();
$db = $database->connect(); // Nessa linha, a variável $db recebe o objeto de conexão da classe DataBase()

$noticia = new Noticia($db);

// Definindo o tipo de conteúdo da resposta
header('Content-Type: text/plain');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os dados do formulário (se estiver tudo ok)
    $manchete = isset($_POST['manchete']) ? $_POST['manchete'] : '';
    $url = isset($_POST['url']) ? $_POST['url'] : '';
    $dataPub = isset($_POST['dataPub']) ? $_POST['dataPub'] : '';

    // Verificação semelhante para pegar a imagem inserida (usando a superglobal $_FILES[])
    $capaNoticia = isset($_FILES['capaNoticia']) ? $_FILES['capaNoticia'] : null;

    // Verifica se os dados estão 'vazios', caso sim, não é criado um registro no banco, só vai pro fim do código
    if (empty($manchete) || empty($url) || empty($dataPub) || !$capaNoticia || $capaNoticia['error'] != 0) {
        echo "Dados incompletos ou imagem não enviada";
        exit;
    }

    // Faz uma verificação se a imagem foi enviada de fato, se sim, armazena como binário
    if ($capaNoticia && $capaNoticia['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/bmp']; // array com os seguintes formatos de imagem

        if (!in_array($capaNoticia['type'], $allowedTypes)) { // Verificando se a imagem NÃO compartilha de um formato em comum a esses definidos
            echo "Formato de imagem inválido";
            exit;
        }

        if ($capaNoticia['size'] > 2 * 1024 * 1024) { // Verifica se a imagem ultrapassa 2MB
            echo "Imagem muito grande. Máximo de 2MB permitido";
            exit;
        }

        // Lê a imagem e armazena como binário
        $capaNoticia = file_get_contents($capaNoticia['tmp_name']);
    } else {
        $capaNoticia = null;
    }

    // 'Seta' os atributos do noticia
    $noticia->setManchete($manchete);
    $noticia->setUrl($url);
    $noticia->setDataPub($dataPub);
    $noticia->setCapa($capaNoticia);

    // Tenta cadastrar o noticia no Bd
    if ($noticia->createNoticia()) {
        echo "success";
    } else {
        echo "Falha ao cadastrar o noticia";
    }
}
?>
