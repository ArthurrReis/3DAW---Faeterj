<?php
require_once 'funçoes.php';

if (isset($_GET['id'])) {
    $index = $_GET['id'];
    $alunos = lerAlunos();

    if (isset($alunos[$index])) {
        unset($alunos[$index]);
        $alunos = array_values($alunos);
        salvarAlunos($alunos);
    }
}

header("Location: index.php");
exit;
