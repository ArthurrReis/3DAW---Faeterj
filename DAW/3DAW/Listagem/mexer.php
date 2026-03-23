<?php
require_once 'funcoes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alunos = lerAlunos();
    
    $novoAluno = [
        'matricula' => htmlspecialchars($_POST['matricula']),
        'nome'      => htmlspecialchars($_POST['nome']),
        'email'     => htmlspecialchars($_POST['email'])
    ];

    $index = $_POST['index_edicao'];

    if ($index !== "") {
        $alunos[$index] = $novoAluno;
    } else {
        $alunos[] = $novoAluno;
    }

    salvarAlunos($alunos);
}

header("Location: index.php");
exit;