<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

if (isset($_SESSION['cliente_id'])) {
    echo json_encode([
        'logado' => true,
        'cliente_id' => $_SESSION['cliente_id'],
        'nome' => $_SESSION['cliente_nome']
    ]);
} else {
    echo json_encode(['logado' => false]);
}