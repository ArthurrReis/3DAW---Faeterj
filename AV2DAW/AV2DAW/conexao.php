<?php
$host = 'localhost';
$db   = 'salao_studiov';
$user = 'root'; 
$pass = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['sucesso' => false, 'mensagem' => 'Erro de conexão com o banco de dados.']));
}
?>