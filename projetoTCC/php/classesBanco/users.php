<?php 
    class Users{
        //Atributos da classe pra ajudar na conexão
        private $cnn;
        private $table_name = "users";

        //Atributos da classe users (tipo, suas características de fato)
        private $user_id;
        private $nome;
        private $email;
        private $senha;
        private $perfil;
        private $status_bd;

        //------------------------------------------------------------------------------------
        //Construtor que inicializa a conexão com o Bd
        public function __construct($db){
            $this->cnn = $db;
        }

        //------------------------------------------------------------------------------------
        // Métodos acessores (getters)
        public function getId() {
            return $this->user_id;
        }

        public function getNome() {
            return $this->nome;
        }

        public function getEmail() {
        return $this->email;    
        }

        public function getSenha() {
            return $this->senha;
        }

        public function getPerfil() {
            return $this->perfil;
        }

        public function getStatus() {
            return $this->status_bd;
        }

        //------------------------------------------------------------------------------------
        // Métodos modificadores (setters)
        public function setNome($nome) {
            $this->nome = $nome;
        }

        public function setEmail($email) {
            $this->email = $email;
        }

        public function setSenha($senha) {
            $this->senha = $senha;
        }

        public function setPerfil($perfil) {
            $this->perfil = $perfil;
        }

        public function setStatus($status_bd) {
            $this->status_bd = $status_bd;
        }

        public function atualizaFoto($id, $img){
            $query = "UPDATE {$this->table_name} SET perfil = :imagem WHERE user_id = :id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':imagem', $img, PDO::PARAM_LOB);

            //Executanto a query
            if($stmt->execute()){
                //echo "Foto atualizada com sucesso!";
                return true;
            }else{
                //echo "Houve um erro ao tentar atualizar a foto!!";
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return false;
            }
        }

        public function puxaImg($id){
            $query =  "SELECT perfil FROM {$this->table_name} WHERE user_id = :id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['perfil'];
            }else{
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return false;
            }
        }

        public function puxaNome($id){
            $query = "SELECT nome FROM {$this->table_name} WHERE user_id = :id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['nome'];
            }else{
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return null;
            }
        }

        public function alteraNome($id, $nome){
            $query = "UPDATE {$this->table_name} SET nome = :nome WHERE user_id = :id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if($stmt->execute()){
                //echo "Nome atualizado com sucesso!";
                return true;
            }else{
                //echo "Houve um erro ao tentar atualizar o nome!!";
                $errorInfo = $stmt->errorInfo();
                echo "Erro ao executar a query: " . $errorInfo[2];
                return false;
            }
        }

        public function deleteUser($user_id){
            $consulta = "DELETE FROM users_anime WHERE cod_user = :cod_user";

            $statement = $this->cnn->prepare($consulta);

            $statement->bindParam(':cod_user', $user_id, PDO::PARAM_INT);
        
            if($statement->execute()){
                $query = "DELETE FROM {$this->table_name} WHERE user_id = :user_id";

                $stmt = $this->cnn->prepare($query);

                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

                if($stmt->execute()){
                    return true;
                }else{
                    return false;
                }
            }else{
                echo "Não foi possível excluir o usuário";
            }

            
        }

        public function desativaUser($user_id){
            $query = "UPDATE {$this->table_name} SET status_bd = 0 WHERE user_id = :user_id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function reativaUser($user_id){
            //$retornaBoolean = false;

            $query = "UPDATE {$this->table_name} SET status_bd = 1 WHERE user_id = :user_id";

            $stmt = $this->cnn->prepare($query);

            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            if($stmt->execute()){
                $query1 = "SELECT * FROM {$this->table_name} WHERE user_id = :user_id";

                $stmt1 = $this->cnn->prepare($query1);

                $stmt1->bindParam(':user_id', $user_id, PDO::PARAM_INT);

                $stmt1->execute();

                $registro = $stmt1->fetch(PDO::FETCH_ASSOC);

                if($registro){
                
                    
                    return $registro;
                }else{
                    return false;
                }
            }else{
                return false;
            }

            //return $retornaBoolean;
        }
    }
?>