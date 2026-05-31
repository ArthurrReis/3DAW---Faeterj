<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['api'])) {
    header('Content-Type: application/json');
    $resposta = ["texto" => [], "multipla" => []];

    $arquivo_texto = "perguntatexto.txt";
    if (file_exists($arquivo_texto)) {
        $linhas = file($arquivo_texto, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($linhas as $linha) {
            if(trim($linha) != "") {
                $resposta["texto"][] = htmlspecialchars(trim($linha));
            }
        }
    }

    $arquivo_multipla = "perguntamultipla.txt";
    if (file_exists($arquivo_multipla)) {
        $linhas = file($arquivo_multipla, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($linhas as $linha) {
