<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $dados = json_decode($input, true);

    if ($dados && isset($dados["nome"]) && isset($dados["email"])) {
        $nome = $dados["nome"];
        $email = $dados["email"];
        $arquivo_db = "usuario.txt";

        if(!file_exists($arquivo_db)){
            $ponteiro = fopen($arquivo_db, "w");
            $header = "Nome;Email\n";
            fwrite($ponteiro, $header);
            fclose($ponteiro);
        }

        $ponteiro = fopen($arquivo_db, "a");
        $linha = "{$nome};{$email}\n";
        
        fwrite($ponteiro, $linha);
        fclose($ponteiro);
        
        header('Content-Type:
