<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require 'conexao.php';

$resposta = ['sucesso' => false, 'mensagem' => ''];

if (!isset($_SESSION['cliente_id'])) {
    $resposta['mensagem'] = 'Você precisa entrar na sua conta para agendar.';
    echo json_encode($resposta);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $resposta['mensagem'] = 'Método não permitido.';
    echo json_encode($resposta);
    exit;
}

$clienteId     = $_SESSION['cliente_id'];
$servicoId     = isset($_POST['servico_id']) ? (int)$_POST['servico_id'] : 0;
$profissional  = isset($_POST['profissional']) ? trim($_POST['profissional']) : '';
$data          = isset($_POST['data']) ? trim($_POST['data']) : '';
$hora          = isset($_POST['hora']) ? trim($_POST['hora']) : '';
$formaPagamento = isset($_POST['forma_pagamento']) ? trim($_POST['forma_pagamento']) : '';

if (!$servicoId || !$profissional || !$data || !$hora || !$formaPagamento) {
    $resposta['mensagem'] = 'Dados incompletos para o agendamento.';
    echo json_encode($resposta);
    exit;
}

// Busca o valor real do serviço no banco (nunca confiamos no valor vindo do cliente)
$stmt = $pdo->prepare("SELECT valor FROM servicos WHERE id = :id");
$stmt->execute([':id' => $servicoId]);
$servico = $stmt->fetch();

if (!$servico) {
    $resposta['mensagem'] = 'Serviço não encontrado.';
    echo json_encode($resposta);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO agendamentos (cliente_id, servico_id, profissional, data_agendamento, hora_agendamento, valor, forma_pagamento) VALUES (:cliente_id, :servico_id, :profissional, :data, :hora, :valor, :forma_pagamento)");
    $stmt->execute([
        ':cliente_id' => $clienteId,
        ':servico_id' => $servicoId,
        ':profissional' => $profissional,
        ':data' => $data,
        ':hora' => $hora,
        ':valor' => $servico['valor'],
        ':forma_pagamento' => $formaPagamento
    ]);
    $resposta['sucesso'] = true;
    $resposta['mensagem'] = 'Agendamento confirmado com sucesso!';
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        $resposta['mensagem'] = 'Esse horário acabou de ser reservado por outra pessoa. Escolha outro horário.';
    } else {
        $resposta['mensagem'] = 'Erro ao confirmar o agendamento.';
    }
}

echo json_encode($resposta);