<?php

    class AnimeGenero{
        //Atributos da classe pra ajudar na conexão
        private $cnn;
        private $table_name = "anime_genero";

        //Atributos da classe anime_genero (tipo, suas características de fato)
        private $cod_anime;
        private $cod_genero;

        //------------------------------------------------------------------------------------
        //Construtor que inicializa a conexão com o Bd
        public function __construct($db){
            $this->cnn = $db;
        }

        //------------------------------------------------------------------------------------
        // Métodos acessores (getters)
        public function getCodAnime() {
            return $this->cod_anime;
        }

        public function getCodGenero() {
            return $this->cod_genero;
        }

        //------------------------------------------------------------------------------------
        // Métodos modificadores (setters)
        public function setCodAnime($cod_anime) {
            $this->cod_anime = $cod_anime;
        }

        public function setCodGenero($cod_genero) {
            $this->cod_genero = $cod_genero;
        }

        //------------------------------------------------------------------------------------
        //Funcao para inserir o genero do anime
        public function insertAnimeGenero(){
                
            $query = "INSERT INTO {$this->table_name} (cod_anime, cod_genero) VALUES(:cod_anime, :cod_genero)";
            
            $stmt = $this->cnn->prepare($query);

            //colocando o id do anime e do genero em variaveis para não haver erro com o metodo bindParam()
            $cod_anime = $this->getCodAnime();
            $cod_genero = $this->getCodGenero();

            $stmt->bindParam(':cod_anime', $cod_anime);
            $stmt->bindParam(':cod_genero', $cod_genero);
               

            //Executanto a query
            if($stmt->execute()){
                //echo "Registro cadastrado com sucesso na tabela anime_genero!";
                return true;
            }else{
                //echo "Houve um erro no cadastro do anime_genero!!";
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return false;
            }
        }

        public function selectGenAnime(){
            //pegando o id do anime na url
            $id_anime = $_GET['id'];

            $query = "SELECT 
                        g.genero AS nome_genero
                    FROM 
                        anime_genero ag 
                    JOIN 
                        anime a ON ag.cod_anime = a.anime_id
                    JOIN 
                        genero g ON ag.cod_genero = g.genero_id
                    WHERE 
                        a.anime_id = :id_anime";
            
            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':id_anime', $id_anime, PDO::PARAM_INT);

            if($stmt->execute()){
                $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                
                //Iniciando um array que vai armazenar os gêneros
                $generos = [];

                foreach($dados as $registro){
                    $generos[] = $registro['nome_genero']; //Adicionando cada genero ao array
                }
                $genero_exibido = implode(', ', $generos);//colocando vírgula no lugar dos espaços
                echo $genero_exibido;
            }else{
                echo "[Erro ao tentar selecionar os gêneros do anime]!!";
            }
            
        }

        public function updateAnimeGenero($anime_id, $genero_ids){
            //deleta os generos antigos    
            $deleteQuery = "DELETE FROM {$this->table_name} WHERE cod_anime = :cod_anime";
            
            $deleteStmt = $this->cnn->prepare($deleteQuery);

            $deleteStmt->bindParam(':cod_anime', $anime_id);

            if(!$deleteStmt->execute()){
                $errorInfo = $deleteStmt->errorInfo();
                echo "Erro ao executar a query de exclusão dos gêneros: " . $errorInfo[2];
                return false;
            }
               
            //insere os novos generos
            $insertQuery = "INSERT INTO anime_genero (cod_anime, cod_genero) VALUES(:cod_anime, :cod_genero)";
            
            $insertStmt = $this->cnn->prepare($insertQuery);

            foreach($genero_ids as $genero_id){
                //o bindValue pode ser usado no foreach, o bindParam não
                $insertStmt->bindValue(':cod_anime', $anime_id, PDO::PARAM_INT);
                $insertStmt->bindValue(':cod_genero', $genero_id, PDO::PARAM_INT);

                if(!$insertStmt->execute()){
                    $errorInfo = $insertStmt->errorInfo();
                    echo "Erro ao executar a query de inserção dos gêneros: " . $errorInfo[2];
                    return false;
                }
            }

            return true; //Sucesso na atualização

        }

    }

?>