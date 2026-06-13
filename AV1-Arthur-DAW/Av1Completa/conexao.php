<?php
$host = "localhost";
$username = "root"; 
$password = "";     
$dbname = "game_corporativo";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    die(json_encode(["status" => "error", "mensagem" => "Falha na conexão com o DB: " . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
?>