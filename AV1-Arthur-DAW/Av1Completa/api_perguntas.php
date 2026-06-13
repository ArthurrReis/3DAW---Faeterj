<?php
header('Content-Type: application/json');
require 'conexao.php';

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    $acao = $_GET["acao"] ?? "listar";
    
    if ($acao === "listar") {
        $sql = "SELECT * FROM perguntas";
        $resultado = $conn->query($sql);
        $perguntas = [];
        
        while($linha = $resultado->fetch_assoc()) {
            $perguntas[] = $linha;
        }
        echo json_encode(["status" => "success", "dados" => $perguntas]);
        exit;
    }
}

if ($metodo === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $acao = $input["acao"] ?? "";
    
    if ($acao === "criar_multipla") {
        $pergunta = $conn->real_escape_string($input["pergunta"]);
        $opA = $conn->real_escape_string($input["opA"]);
        $opB = $conn->real_escape_string($input["opB"]);
        $opC = $conn->real_escape_string($input["opC"]);
        $opD = $conn->real_escape_string($input["opD"]);
        $gab = $conn->real_escape_string($input["gabarito"]);
        
        $sql = "INSERT INTO perguntas (tipo, pergunta, opA, opB, opC, opD, gabarito) 
                VALUES ('multipla', '$pergunta', '$opA', '$opB', '$opC', '$opD', '$gab')";
                
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "mensagem" => "Criada com sucesso!"]);
        } else {
            echo json_encode(["status" => "error", "mensagem" => $conn->error]);
        }
        exit;
    }
    
    if ($acao === "excluir") {
        $id = intval($input["id"]);
        $sql = "DELETE FROM perguntas WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "mensagem" => "Pergunta deletada!"]);
        }
        exit;
    }
    
}
?>