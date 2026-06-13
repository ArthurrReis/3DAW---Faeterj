<?php
header('Content-Type: application/json');
require 'conexao.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input["acao"]) && $input["acao"] === "criar") {
        
        $nome = $conn->real_escape_string($input["nome"]);
        $email = $conn->real_escape_string($input["email"]);
        
        $sql = "INSERT INTO usuarios (nome, email) VALUES ('$nome', '$email')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "mensagem" => "Usuário salvo no banco!"]);
        } else {
            echo json_encode(["status" => "error", "mensagem" => "Erro DB: " . $conn->error]);
        }
        exit;
    }
}
?>