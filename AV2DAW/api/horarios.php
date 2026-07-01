<?php
header('Content-Type: application/json; charset=utf-8');
require 'conexao.php';

$data = isset($_GET['data']) ? $_GET['data'] : '';
$profissional = isset($_GET['profissional']) ? $_GET['profissional'] : '';

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
    echo json_encode(['ocupados' => []]);
    exit;
}

if ($profissional === '' || $profissional === 'Qualquer') {
    echo json_encode(['ocupados' => []]);
    exit;
}

$stmt = $pdo->prepare("SELECT DATE_FORMAT(hora_agendamento, '%H:%i') AS hora FROM agendamentos WHERE data_agendamento = :data AND profissional = :profissional AND status != 'cancelado'");
$stmt->execute([':data' => $data, ':profissional' => $profissional]);
$ocupados = array_column($stmt->fetchAll(), 'hora');

echo json_encode(['ocupados' => $ocupados]);