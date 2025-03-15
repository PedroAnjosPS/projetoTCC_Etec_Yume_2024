<?php
session_start();
require_once "users.php";

// Captura o corpo da requisição JSON
$input = file_get_contents('php://input'); 

// Depuração: Registra os dados recebidos
error_log("Received data: " . $input);

if ($input) {
    $dados = json_decode($input);

    // Depuração: Verifica se os dados foram decodificados corretamente
    if (json_last_error() === JSON_ERROR_NONE) {
        $nome = $dados->n;
        $email = $dados->e;
        $senha = $dados->s;

        $comandoSql = "INSERT INTO users (nome, email, senha)
                       VALUES('$nome', '$email', '$senha')";

        $resultado = mysqli_query($GLOBALS['cnn'], $comandoSql);

        if (mysqli_affected_rows($GLOBALS['cnn']) > 0) {
            echo json_encode(["status" => "success"]);
            $_SESSION['nome_usuario'] = $nome;
        } else {
            echo json_encode(["status" => "failed", "error" => "Database insertion failed"]);
        }
    } else {
        echo json_encode(["status" => "failed", "error" => "Invalid JSON data"]);
    }
} else {
    echo json_encode(["status" => "failed", "error" => "No data received"]);
}
?>
