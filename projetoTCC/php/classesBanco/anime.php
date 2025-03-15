<?php 
    class Anime{
        //Atributos da classe pra ajudar na conexão
        private $cnn;
        private $table_name = "anime";

        //Atributos da classe anime (tipo, suas características de fato)
        private $anime_id;
        private $titulo;
        private $descricao;
        private $capa;
        private $qt_ep;
        private $data_lanc;

        //------------------------------------------------------------------------------------
        //Construtor que inicializa a conexão com o Bd
        public function __construct($db){
            $this->cnn = $db;
        }

        //------------------------------------------------------------------------------------
        // Métodos acessores (getters)
        public function getId() {
            return $this->anime_id;
        }

        public function getTitulo() {
            return $this->titulo;
        }

        public function getDescricao() {
        return $this->descricao;    
        }

        public function getCapa() {
            return $this->capa;
        }

        public function getQtEp() {
            return $this->qt_ep;
        }

        public function getDataLanc() {
            return $this->data_lanc;
        }

        //------------------------------------------------------------------------------------
        // Métodos modificadores (setters)
        public function setTitulo($titulo) {
            $this->titulo = $titulo;
        }

        public function setDescricao($descricao) {
            $this->descricao = $descricao;
        }

        public function setCapa($capa) {
            $this->capa = $capa;
        }

        public function setQtEp($qt_ep) {
            $this->qt_ep = $qt_ep;
        }

        public function setDataLanc($data_lanc) {
            $this->data_lanc = $data_lanc;
        }

        //------------------------------------------------------------------------------------
        //método para criar um novo anime no Bd
        public function createAnime(){

            $query = "INSERT INTO {$this->table_name} (titulo, descricao, capa, qt_ep, data_lanc)
            VALUES (:titulo, :descricao, :capa, :qt_ep, :data_lanc)";

            $stmt = $this->cnn->prepare($query);

            /*O método bindParam() serve para vincular os parametros da classe com os da consulta (query), assim, há maior segurança,
             pq vc não está passando os valores diretamente (igual costumamos fazer em aula)*/
            $stmt->bindParam(':titulo', $this->titulo);
            $stmt->bindParam(':descricao', $this->descricao);
            // Usando bindParam para o campo blob com o tipo PDO::PARAM_LOB
            $stmt->bindParam(':capa', $this->capa, PDO::PARAM_LOB);
            $stmt->bindParam(':qt_ep', $this->qt_ep, PDO::PARAM_INT);
            $stmt->bindParam(':data_lanc', $this->data_lanc);

            //Executanto a query
            if($stmt->execute()){
                //echo "Anime cadastrado com sucesso!";
                return true;
            }else{
                //echo "Houve um erro no cadastro do anime!!";
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return false;
            }
        }

        //método para selecionar arquivos no index
        public function exibirAnimeIndex($adm){
            $query = "SELECT anime_id, titulo, capa FROM {$this->table_name} ORDER BY RAND() LIMIT 8;";

            $stmt = $this->cnn->prepare($query);

            if($stmt->execute()){
                //echo "Anime selecionado com sucesso!";
                //return true;

                while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){

                    $id_anime = $dados["anime_id"];
                    $nome_anime = $dados["titulo"];
                    $capa_anime = $dados["capa"];

                    //adciona um nome pra a imagem
                    $nomeImg = uniqid('foto_de_perfil_', true) . '.jpg';

                    //caminho aonde será salvo a imagem que será decodificada 
                    $pathImg = '../imgs/capas/' . $nomeImg;

                    //decodificando o arquivo binário que foi pego do banco e salvando na pasta imgs
                    file_put_contents($pathImg, $capa_anime);

                    echo ($adm === '1') ? 
                        "<a href='animeAdm.php?id=$id_anime' style='text-decoration: none;'>" :
                        "<a href='animeInfo.php?id=$id_anime' style='text-decoration: none;'>";
                    
                    echo        "
                                    <div class='previewAnime'>
                                        <img src='$pathImg' alt='poster teste'>
                                        <h1>$nome_anime</h1>
                                    </div>
                                </a>";
                }

            }else{
                //echo "Falha ao tentar selecionar anime!";
                echo "<span style='background-color: red;'>Não foi possível selecionar as informações do anime!!</span>";
            }
        }

        public function exibirAnimeInfo(){

            try{
                
                $id_anime = $_GET['id'];
                $query = "SELECT * FROM {$this->table_name} WHERE anime_id = :id";

                $stmt = $this->cnn->prepare($query);
                $stmt->bindParam(':id', $id_anime, PDO::PARAM_INT);

                if($stmt->execute()){
                    if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $titulo = $dados["titulo"];
                        $descricao = $dados["descricao"];
                        $capa = $dados["capa"];
                        $qt_ep = $dados["qt_ep"];
                        $data_lanc = $dados["data_lanc"];

                        //adciona um nome pra a imagem
                        $nomeImg = uniqid('foto_de_perfil_', true) . '.jpg';

                        //caminho aonde será salvo a imagem que será decodificada 
                        $pathImg = '../imgs/capas/' . $nomeImg;

                        //decodificando o arquivo binário que foi pego do banco e salvando na pasta capas
                        file_put_contents($pathImg, $capa);

                        //Formatando a data do anime
                        $data_formatada = (new DateTime($data_lanc))->format('d/m/Y');

                        //Setando essas váriveis dentro dos atributos da classe para serem chamadas no 'html'
                        $this->setTitulo($titulo);
                        $this->setDescricao($descricao);
                        $this->setCapa($pathImg);
                        $this->setQtEp($qt_ep);
                        $this->setDataLanc($data_formatada);
                    }else{
                        echo "Erro ao tentar selecionar as informações do anime!!";
                    }

                }else{
                        echo "<span style='background-color: red;'>Não foi possível selecionar as informações do anime!!</span>";
                }


            }catch(PDOException $e){
                echo "Erro ao tentar executar a query: " . $e->getMessage();
            }

            
        }

        public function updateAnime($anime_id){
            $query = "UPDATE {$this->table_name} SET titulo = :titulo, descricao = :descricao, capa = :capa, qt_ep = :qt_ep,
                      data_lanc = :data_lanc WHERE anime_id = :anime_id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':titulo', $this->getTitulo());
            $stmt->bindParam(':descricao', $this->getDescricao());
            $stmt->bindParam(':capa', $this->getCapa());
            $stmt->bindParam(':qt_ep', $this->getQtEp());
            $stmt->bindParam(':data_lanc', $this->getDataLanc());
            $stmt->bindParam(':anime_id', $anime_id);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function pegaBlob($anime_id){
            $query = "SELECT capa FROM {$this->table_name} WHERE anime_id = :anime_id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':anime_id', $anime_id, PDO::PARAM_INT);

            if($stmt->execute()){
                $blob = $stmt->fetch(PDO::FETCH_ASSOC);
                return $blob['capa']; 
            }else{
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return false;
            }
        }

        public function exibirAnimesSalvos($codsAnime){
            $temAnimesSalvos = false;

            foreach($codsAnime as $animesSalvos){
                $query = "SELECT * FROM anime WHERE anime_id = :anime_id";

                $stmt = $this->cnn->prepare($query);

                $stmt->bindValue(':anime_id', $animesSalvos, PDO::PARAM_INT);

                if($stmt->execute()){
                    if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $temAnimesSalvos = true;

                        $id_anime = $dados["anime_id"];
                        $titulo = $dados["titulo"];
                        //$descricao = $dados["descricao"];
                        $capa = $dados["capa"];
                        //$qt_ep = $dados["qt_ep"];
                        //$data_lanc = $dados["data_lanc"];

                        //adciona um nome pra a imagem
                        $nomeImg = uniqid('foto_de_perfil_', true) . '.jpg';

                        //caminho aonde será salvo a imagem que será decodificada 
                        $pathImg = '../imgs/capas/' . $nomeImg;

                        //decodificando o arquivo binário que foi pego do banco e salvando na pasta capas
                        file_put_contents($pathImg, $capa);

                        //pegando a media de cada anime
                        $consultaSql = "CALL calcularMediaAval(:cod_anime)";

                        $stmtMedia = $this->cnn->prepare($consultaSql);

                        $stmtMedia->bindValue(':cod_anime', $animesSalvos, PDO::PARAM_INT);

                        $stmtMedia->execute();

                        if($dados = $stmtMedia->fetch(PDO::FETCH_ASSOC)){
                            $mediaAval = $dados['media_porcentagem'];
                            $mediaAvalFormatada = number_format($mediaAval, 1, ',', '.');
                        }else{
                            $mediaAvalFormatada = 'Média não encontrada!!';
                        }
                        
                        echo "
                        <a href='animeInfo.php?id=$animesSalvos' style='text-decoration: none; color: black;'>
                        <div class='resultAnime'>
                          <img src='$pathImg' alt='img teste'>
                        
                          <p class='animeDetails'>$titulo</p>
                        
                          <div class='details no-link'>
                            <div class='nota no-link'>
                                <abbr title='Pontuação do público'><span id='notaGeral'>$mediaAvalFormatada%</span></abbr>
                            </div>
                            <button class='excluir no-link' data-id='$id_anime'><img src='../imgs/icons/lixeira.png' alt='salvar'></button>
                          </div>
                        </div>
                      </a>
                        ";

                      //return true;
                    }else{
                        echo "Erro ao tentar selecionar as informações do anime!!";
                    }

                }else{
                        echo "<span style='background-color: red;'>Não foi possível selecionar as informações do anime!!</span>";
                }

            }
            return $temAnimesSalvos; //Retorna se há ou não algum anime salvo. Essa variavel ajuda a resolver um problema de condição na pagina html
        }

        public function exibirTitulosSemel($anime_id, $adm){
            $query = "CALL selecionaAnimesGenSemel(:anime_id)";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':anime_id', $anime_id, PDO::PARAM_INT);

            if($stmt->execute()){
                //echo "Anime selecionado com sucesso!";
                //return true;

                while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){

                    $id_anime = $dados["anime_id"];
                    $nome_anime = $dados["titulo"];
                    $capa_anime = $dados["capa"];

                    //adciona um nome pra a imagem
                    $nomeImg = uniqid('foto_de_perfil_', true) . '.jpg';

                    //caminho aonde será salvo a imagem que será decodificada 
                    $pathImg = '../imgs/capas/' . $nomeImg;

                    //decodificando o arquivo binário que foi pego do banco e salvando na pasta imgs
                    file_put_contents($pathImg, $capa_anime);

                    echo ($adm === '1') ? 
                        "<a href='animeAdm.php?id=$id_anime' style='text-decoration: none;'>" :
                        "<a href='animeInfo.php?id=$id_anime' style='text-decoration: none;'>";
                    
                    echo        "   <div class='previewAnime'>
                                        <img src='$pathImg' alt='poster teste'>
                                        <h1>$nome_anime</h1>
                                    </div>
                                </a>";
                }

            }else{
                //echo "Falha ao tentar selecionar anime!";
                echo "<span style='background-color: red;'>Não foi possível selecionar as informações do anime!!</span>";
            }
        }

        public function selectNomesAnimes(){
            $query = "SELECT titulo FROM anime";

            $stmt = $this->cnn->prepare($query);

            if($stmt->execute()){
                $titulos = [];

                while($registro = $stmt->fetch(PDO::FETCH_ASSOC)){
                    array_push($titulos, $registro['titulo']);
                }

                return json_encode($titulos);
            }else{
                return false;
            }
        }

        public function selectAnimeByName($nome_anime, $adm, $id_user){
            $resultadoBusca = false;

            $query = "SELECT * FROM anime 
                      WHERE titulo LIKE :titulo";
            
            $stmt = $this->cnn->prepare($query);

            $stmt->bindValue(':titulo', '%' . $nome_anime . '%', PDO::PARAM_STR_CHAR);

            if($stmt->execute()){
                while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $resultadoBusca = true;

                    $anime_id = $dados['anime_id'];
                    $titulo = $dados["titulo"];
                    //$descricao = $dados["descricao"];
                    $capa = $dados["capa"];
                    //$qt_ep = $dados["qt_ep"];
                    //$data_lanc = $dados["data_lanc"];

                    //adciona um nome pra a imagem
                    $nomeImg = uniqid('foto_de_perfil_', true) . '.jpg';

                    //caminho aonde será salvo a imagem que será decodificada 
                    $pathImg = '../imgs/capas/' . $nomeImg;

                    //decodificando o arquivo binário que foi pego do banco e salvando na pasta capas
                    file_put_contents($pathImg, $capa);

                    //pegando a media de cada anime
                    $consultaSql = "CALL calcularMediaAval(:cod_anime)";

                    $stmtMedia = $this->cnn->prepare($consultaSql);

                    $stmtMedia->bindValue(':cod_anime', $anime_id, PDO::PARAM_INT);

                    $stmtMedia->execute();

                    if($dados = $stmtMedia->fetch(PDO::FETCH_ASSOC)){
                        $mediaAval = $dados['media_porcentagem'];
                        $mediaAvalFormatada = number_format($mediaAval, 1, ',', '.');
                    }else{
                        $mediaAvalFormatada = 'Média não encontrada!!';
                    }
                     
                    
                    if($id_user){

                        if($adm === '1'){

                            echo "<a href='animeAdm.php?id=$anime_id' style='text-decoration: none; color: black;'>
                                <div class='resultAnime'>
                                    <img src='$pathImg'>
          
                                    <p class='animeDetails'>$titulo</p>
          
                                    <div class='details no-link'>
                                        <div class='nota no-link'>
                                            <abbr title='Pontuação do público'><span id='notaGeral'>$mediaAvalFormatada%</span></abbr>
                                        </div>
                                    
                                    </div>
                                </div>
                            </a>";

                        }else{

                            echo "<a href='animeInfo.php?id=$anime_id' style='text-decoration: none; color: black;'>
                                <div class='resultAnime'>
                                    <img src='$pathImg'>
          
                                    <p class='animeDetails'>$titulo</p>
          
                                    <div class='details no-link'>
                                        <div class='nota no-link'>
                                            <abbr title='Pontuação do público'><span id='notaGeral'>$mediaAvalFormatada%</span></abbr>
                                        </div>

                                        <abbr title='Salvar anime?' id='texto-abbr'>
                                            <button id='animeSave' value='$anime_id'>
                                                <i class='far fa-bookmark'></i>
                                            </button>
                                        </abbr>

                                    </div>
                                </div>
                            </a>";

                        }

                    }else{

                        echo "<a href='animeInfo.php?id=$anime_id' style='text-decoration: none; color: black;'>
                                <div class='resultAnime'>
                                    <img src='$pathImg'>
          
                                    <p class='animeDetails'>$titulo</p>
          
                                    <div class='details no-link'>
                                        <div class='nota no-link'>
                                            <abbr title='Pontuação do público'><span id='notaGeral'>$mediaAvalFormatada%</span></abbr>
                                        </div>

                                    </div>
                                </div>
                            </a>";

                    }

                    //return true;
                }
            }else{
                //return false;
                echo "Erro ao executar a query!!";
            }

            return $resultadoBusca;
        }

        public function deleteAnime($anime_id){
            $consulta = "DELETE FROM anime_genero WHERE cod_anime = :cod_anime";

            $statement = $this->cnn->prepare($consulta);

            $statement->bindParam(':cod_anime', $anime_id, PDO::PARAM_INT);
        
            if($statement->execute()){

                $query = "DELETE FROM users_anime WHERE cod_anime = :cod_anime";

                $stmt = $this->cnn->prepare($query);

                $stmt->bindParam(':cod_anime', $anime_id, PDO::PARAM_INT);

                if($stmt->execute()){
                    $query1 = "DELETE FROM anime WHERE anime_id = :anime_id";

                    $stmt1 = $this->cnn->prepare($query1);

                    $stmt1->bindParam(':anime_id', $anime_id, PDO::PARAM_INT);

                    if($stmt1->execute()){
                        return true;
                    }else{
                        return false;
                    }

                }else{
                    echo "Não foi possível excluir o anime";
                }
            }else{
                echo "Não foi possível excluir o anime";
            }

            
        }

    }
    
?>