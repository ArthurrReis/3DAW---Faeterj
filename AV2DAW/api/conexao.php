<?php
$host = 'localhost';
$db   = 'salao_studiov';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(['sucesso' => false, 'mensagem' => 'Erro de conexão com o banco de dados.']));
}