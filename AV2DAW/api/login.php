<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require 'conexao.php';

$resposta = ['sucesso' => false, 'mensagem' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $resposta['mensagem'] = 'Método não permitido.';
    echo json_encode($resposta);
    exit;
}

$cpf   = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

if (empty($cpf) || empty($senha)) {
    $resposta['mensagem'] = 'Preencha CPF e senha.';
    echo json_encode($resposta);
    exit;
}

$stmt = $pdo->prepare("SELECT id, nome_completo, senha_hash FROM clientes WHERE cpf = :cpf");
$stmt->execute([':cpf' => $cpf]);
$cliente = $stmt->fetch();

if ($cliente && password_verify($senha, $cliente['senha_hash'])) {
    $_SESSION['cliente_id'] = $cliente['id'];
    $_SESSION['cliente_nome'] = $cliente['nome_completo'];
    $resposta['sucesso'] = true;
    $resposta['mensagem'] = 'Login realizado com sucesso!';
    $resposta['nome'] = $cliente['nome_completo'];
} else {
    $resposta['mensagem'] = 'CPF ou senha incorretos.';
}

echo json_encode($resposta);