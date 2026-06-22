<?php
header('Content-Type: application/json; charset=utf-8');
require 'conexao.php';

$resposta = ['sucesso' => false, 'mensagem' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $cpf = isset($_POST['cpf']) ? trim($_POST['cpf']) : '';
    $nascimento = isset($_POST['nascimento']) ? trim($_POST['nascimento']) : '';
    $cartao = isset($_POST['cartao']) ? trim($_POST['cartao']) : '';

    if (empty($nome) || empty($cpf) || empty($nascimento) || empty($cartao)) {
        $resposta['mensagem'] = 'Todos os campos são obrigatórios.';
        echo json_encode($resposta);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO clientes (nome_completo, cpf, data_nascimento, cartao) VALUES (:nome, :cpf, :nascimento, :cartao)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':nascimento', $nascimento);
        $stmt->bindParam(':cartao', $cartao);

        if ($stmt->execute()) {
            $resposta['sucesso'] = true;
            $resposta['mensagem'] = 'Cliente cadastrado com sucesso! Bem-vindo ao Studio V.';
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $resposta['mensagem'] = 'Este CPF já está cadastrado.';
        } else {
            $resposta['mensagem'] = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    }
} else {
    $resposta['mensagem'] = 'Método não permitido.';
}

echo json_encode($resposta);
?>