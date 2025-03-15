<?php 
    class Noticia{
        //Atributos da classe pra ajudar na conexão
        private $cnn;
        private $table_name = "noticia";

        //Atributos da classe anime (tipo, suas características de fato)
        private $noticia_id;
        private $manchete;
        private $url;
        private $capa;
        private $dataPub;

        //------------------------------------------------------------------------------------
        //Construtor que inicializa a conexão com o Bd
        public function __construct($db){
            $this->cnn = $db;
        }

        //------------------------------------------------------------------------------------
        // Métodos acessores (getters)
        public function getId() {
            return $this->noticia_id;
        }

        public function getManchete() {
            return $this->manchete;
        }

        public function getUrl() {
        return $this->url;    
        }

        public function getCapa() {
            return $this->capa;
        }


        public function getDataPub() {
            return $this->dataPub;
        }

        //------------------------------------------------------------------------------------
        // Métodos modificadores (setters)
        public function setManchete($manchete) {
            $this->manchete = $manchete;
        }

        public function setUrl($url) {
            $this->url = $url;
        }

        public function setCapa($capa) {
            $this->capa = $capa;
        }

        public function setDataPub($dataPub) {
            $this->dataPub = $dataPub;
        }

        //------------------------------------------------------------------------------------
        //método para criar um novo anime no Bd
        public function createNoticia(){

            $query = "INSERT INTO {$this->table_name} (manchete, url, data_pub, capa)
            VALUES (:manchete, :url, :data_pub, :capa)";

            $stmt = $this->cnn->prepare($query);

            /*O método bindParam() serve para vincular os parametros da classe com os da consulta (query), assim, há maior segurança,
             pq vc não está passando os valores diretamente (igual costumamos fazer em aula)*/
            $stmt->bindParam(':manchete', $this->manchete);
            $stmt->bindParam(':url', $this->url);
            // Usando bindParam para o campo blob com o tipo PDO::PARAM_LOB
            $stmt->bindParam(':data_pub', $this->dataPub);
            $stmt->bindParam(':capa', $this->capa, PDO::PARAM_LOB);

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

        public function exibirNoticiaInfo(){

            try{
                
                $noticia_id = $_GET['id'];
                $query = "SELECT * FROM {$this->table_name} WHERE noticia_id = :id";

                $stmt = $this->cnn->prepare($query);
                $stmt->bindParam(':id', $noticia_id, PDO::PARAM_INT);

                if($stmt->execute()){
                    if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $manchete = $dados["manchete"];
                        $url = $dados["url"];
                        $capa = $dados["capa"];
                        $dataPub = $dados["data_pub"];

                        //adciona um nome pra a imagem
                        $nomeImg = uniqid('foto_de_perfil_', true) . '.jpg';

                        //caminho aonde será salvo a imagem que será decodificada 
                        $pathImg = '../imgs/capas/' . $nomeImg;

                        //decodificando o arquivo binário que foi pego do banco e salvando na pasta imgs
                        file_put_contents($pathImg, $capa);

                        //Formatando a data do anime
                        $data_formatada = (new DateTime($dataPub))->format('d/m/Y');

                        //Setando essas váriveis dentro dos atributos da classe para serem chamadas no 'html'
                        $this->setManchete($manchete);
                        $this->setUrl($url);
                        $this->setCapa($pathImg);
                        $this->setDataPub($data_formatada);
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

        public function exibirNoticiaIndex($adm){
            $query = "SELECT noticia_id, manchete, url, capa FROM {$this->table_name} 
                      ORDER BY data_pub DESC
                      LIMIT 8;";

            $stmt = $this->cnn->prepare($query);

            if($stmt->execute()){
                //echo "Anime selecionado com sucesso!";
                //return true;

                while($dados = $stmt->fetch(PDO::FETCH_ASSOC)){

                    $noticia_id = $dados['noticia_id'];
                    $manchete = $dados['manchete'];
                    $url = $dados['url'];
                    $capa = $dados['capa'];

                    //adciona um nome pra a imagem
                    $nomeImg = uniqid('foto_de_perfil_', true) . '.jpg';

                    //caminho aonde será salvo a imagem que será decodificada 
                    $pathImg = '../imgs/capas/' . $nomeImg;

                    //decodificando o arquivo binário que foi pego do banco e salvando na pasta imgs
                    file_put_contents($pathImg, $capa);

                    echo ($adm === '1') ? 
                        "<a href='telaEditarNoticia.php?id=$noticia_id' style='text-decoration: none;'>" :
                        "<a href='$url' target='_blank' style='text-decoration: none;'>";
                    
                    echo        "
                                    <div class='previewNoticia'>
                                        <img src='$pathImg' alt='poster teste'>
                                        <h1>$manchete</h1>
                                    </div>
                                </a>";
                }

            }else{
                //echo "Falha ao tentar selecionar anime!";
                return false;
            }
        }


        public function pegaBlob($noticia_id){
            $query = "SELECT capa FROM {$this->table_name} WHERE noticia_id = :noticia_id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':noticia_id', $noticia_id, PDO::PARAM_INT);

            if($stmt->execute()){
                $blob = $stmt->fetch(PDO::FETCH_ASSOC);
                return $blob['capa']; 
            }else{
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return false;
            }
        }

        public function updateNoticia($noticia_id){
            $query = "UPDATE {$this->table_name} SET manchete = :manchete, url = :url, data_pub = :data_pub, capa = :capa
                      WHERE noticia_id = :noticia_id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':manchete', $this->getManchete());
            $stmt->bindParam(':url', $this->getUrl());
            $stmt->bindParam(':data_pub', $this->getDataPub());
            $stmt->bindParam(':capa', $this->getCapa());
            $stmt->bindParam(':noticia_id', $noticia_id);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }


        public function deleteNoticia($noticia_id){
            $query = "DELETE FROM noticia WHERE noticia_id = :noticia_id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':noticia_id', $noticia_id, PDO::PARAM_INT);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

    }
    
?>