<?php 
    class UsersAnime{
        //Atributos da classe pra ajudar na conexão
        private $cnn;
        private $table_name = "users_anime";

        //Atributos da classe users_anime (tipo, suas características de fato)
        private $cod_user;
        private $cod_anime;
        private $classificacao;
        private $salvo;

        //------------------------------------------------------------------------------------
        //Construtor que inicializa a conexão com o Bd
        public function __construct($db){
            $this->cnn = $db;
        }

        //------------------------------------------------------------------------------------
        // Métodos acessores (getters)
        public function getCodUser() {
            return $this->cod_user;
        }

        public function getCodAnime() {
            return $this->cod_anime;
        }

        public function getClassificacao(){
            return $this->classificacao;
        }

        public function getSalvo(){
            return $this->salvo;
        }

        //------------------------------------------------------------------------------------
        // Métodos modificadores (setters)
        public function setCodUser($cod_user) {
            $this->cod_user = $cod_user;
        }

        public function setCodAnime($cod_anime) {
            $this->cod_anime = $cod_anime;
        }

        public function setClassificacao($classificacao){
            $this->classificacao = $classificacao;
        }

        public function setSalvo($salvo){
            $this->salvo = $salvo;
        }

        //------------------------------------------------------------------------------------
        //Funcao para salvar um anime
        public function salvarAnime($user_id, $anime_id){
            $query = "INSERT INTO {$this->table_name} (cod_user, cod_anime, salvo) VALUES(:cod_user, :cod_anime, 1)
            ON DUPLICATE KEY UPDATE salvo = 1";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':cod_user', $user_id);
            $stmt->bindParam(':cod_anime', $anime_id);

            if($stmt->execute()){
                //echo "Anime salvo com sucesso!";
                return true;
            }else{
                //echo "Houve um erro ao tentar salvar o anime!!";
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return false;
            }
        }

        //funcao para alterar o status de salvo do anime
        public function alterarSalvoAnime($user_id, $anime_id){
            $query = "UPDATE users_anime SET salvo = 0 WHERE cod_user = :cod_user AND cod_anime = :cod_anime";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':cod_user', $user_id);
            $stmt->bindParam(':cod_anime', $anime_id);

            if($stmt->execute()){
                //echo "Status de salvo do anime alterado com sucesso!";
                return true;
            }else{
                //echo "Houve um erro ao tentar alterar o status de salvo do anime!!";
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return false;
            }
        }

        //funcao para ver se o anime e está salvo ou não
        public function isAnimeSalvo($user_id, $anime_id){
            $query = "SELECT salvo FROM {$this->table_name} WHERE cod_user = :cod_user AND cod_anime = :cod_anime";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':cod_user', $user_id);
            $stmt->bindParam(':cod_anime', $anime_id);

            $stmt->execute();

            if($stmt->rowCount() > 0){
                $salvo = $stmt->fetch(PDO::FETCH_ASSOC);
                return $salvo['salvo'] == 1; //Retorna um valor booleano. 'True' se o anime estiver salvo e 'false' se não estiver.
            }
            return false;
        }

        public function salvarNota($user_id, $anime_id, $notaAnime){
            try{
                //Inseri ou atualiza a nota (depende se já existe registro ou não)
                $query = "INSERT INTO users_anime (cod_user, cod_anime, classificacao)
                          VALUES (:cod_user, :cod_anime, :classificacao)
                          ON DUPLICATE KEY UPDATE classificacao = VALUES(classificacao)";

                $stmt = $this->cnn->prepare($query);

                $stmt->bindParam(':cod_user', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':cod_anime', $anime_id, PDO::PARAM_INT);
                $stmt->bindParam(':classificacao', $notaAnime, PDO::PARAM_INT);

                if($stmt->execute()){
                    //echo "Nota salva com sucesso!";
                    return true;
                }else{
                    //echo "Erro ao salvar a nota do anime!";
                    return false;
                }
            }catch(PDOException $erro){
                return ['success' => false, 'message' => 'Erro: ' . $erro->getMessage()];
            }
        }

        public function pegarNota($user_id, $anime_id){
            $query = "SELECT classificacao FROM {$this->table_name} 
                      WHERE cod_user = :cod_user 
                      AND cod_anime = :cod_anime";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':cod_user', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':cod_anime', $anime_id, PDO::PARAM_INT);

            if($stmt->execute()){
                $notaUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                //Verifica se a nota foi realmente encontrada
                if($notaUsuario && isset($notaUsuario['classificacao'])){
                    return $notaUsuario['classificacao'];
                }else{
                    return null;
                }
            }else{
                return null;
            }
        }

        public function chamaMediaAval($anime_id){
            $query = "CALL calcularMediaAval(:cod_anime)";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':cod_anime', $anime_id, PDO::PARAM_INT);

            $stmt->execute();

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if($dados){
                $mediaAval = $dados['media_porcentagem'];
                $mediaAvalFormatada = number_format($mediaAval, 1, ',', '.');
                return $mediaAvalFormatada;
            }else{
                return null;
            }
        }

        public function retornaAnimesSalvos($user_id){
            $query = "SELECT cod_anime FROM users_anime 
                      WHERE cod_user = :cod_user AND salvo = 1";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':cod_user', $user_id, PDO::PARAM_INT);

            $animesSalvos = [];

            if($stmt->execute()){
                while($registros = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $cod_anime = $registros['cod_anime'];

                    array_push($animesSalvos, $cod_anime);
                }

                return $animesSalvos;
            }else{
                return false;
            }

        }

        public function removeAniLista($user_id, $anime_id){
            $query = "UPDATE users_anime SET salvo = 0 WHERE cod_user = :cod_user AND cod_anime = :cod_anime";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':cod_user', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':cod_anime', $anime_id, PDO::PARAM_INT);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
    }
?>