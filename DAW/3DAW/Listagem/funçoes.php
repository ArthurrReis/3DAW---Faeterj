<?php
$arquivo = 'alunos.txt';

function lerAlunos() {
    global| $arquivo;
    if (!file_exists($arquivo)) return [];
    
    $dados = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $alunos = [];
    
    foreach ($dados as $linha) {
        $partes = explode('|', $linha);
        $alunos[] = [
            'matricula' => $partes[0],
            'nome'      => $partes[1],
            'email'     => $partes[2]
        ];
    }
    return $alunos;
}

function salvarAlunos($alunos) {
    global $arquivo;
    $linhas = [];
    foreach ($alunos as $aluno) {
        $linhas[] = implode('|', $aluno);
    }
    file_put_contents($arquivo, implode(PHP_EOL, $linhas) . PHP_EOL);
}
?>