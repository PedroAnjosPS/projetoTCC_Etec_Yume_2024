<?php 
    class DataBase{
        private $host = "localhost";
        private $db_name = "Yume";
        private $username = "root";
        private $password = "";
        public $cnn;

        public function connect(){
            $this->cnn = null;

            try{
                $this->cnn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password); /*
                estabelece a conexão com o banco a partir da instancia da classe PDO --> é uma classe PHP que fornece uma interface 
                para acessar bancos de dados.*/

                $this->cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /*Nessa linha estamos configurando atributos da
                conexão. A constante 'ATTR_ERRMODE' define o modo de erro da classe PDO e, a 'ERRMODE_EXCEPTION', define
                com que a classe PDO lance excessões caso haja erros na conexão com o banco.*/

            }catch(PDOException $e){
                echo "Erro na conexão: " . $e->getMessage();
            }

            return $this->cnn;
        }
    }
?>