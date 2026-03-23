<?php 
require_once 'funcoes.php';
$alunos = lerAlunos();

$alunoEdicao = null;
$indexEdicao = "";

if (isset($_GET['editar'])) {
    $indexEdicao = $_GET['editar'];
    if (isset($alunos[$indexEdicao])) {
        $alunoEdicao = $alunos[$indexEdicao];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Alunos TXT</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        form { background: #f4f4f4; padding: 20px; border-radius: 8px; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .btn-edit { background: #ffc107; color: black; padding: 5px; text-decoration: none; }
        .btn-del { background: #dc3545; color: white; padding: 5px; text-decoration: none; }
    </style>
</head>
<body>

    <h2><?= $alunoEdicao ? "Editar Aluno" : "Novo Aluno" ?></h2>
    
    <form action="processar.php" method="POST">
        <input type="hidden" name="index_edicao" value="<?= $indexEdicao ?>">
        <input type="text" name="matricula" placeholder="Matrícula" required value="<?= $alunoEdicao['matricula'] ?? '' ?>">
        <input type="text" name="nome" placeholder="Nome" required value="<?= $alunoEdicao['nome'] ?? '' ?>">
        <input type="email" name="email" placeholder="E-mail" required value="<?= $alunoEdicao['email'] ?? '' ?>">
        <button type="submit"><?= $alunoEdicao ? "Atualizar" : "Cadastrar" ?></button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Matrícula</th><th>Nome</th><th>E-mail</th><th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alunos as $index => $aluno): ?>
            <tr>
                <td><?= $aluno['matricula'] ?></td>
                <td><?= $aluno['nome'] ?></td>
                <td><?= $aluno['email'] ?></td>
                <td>
                    <a href="index.php?editar=<?= $index ?>" class="btn-edit">Editar</a>
                    <a href="excluir.php?id=<?= $index ?>" class="btn-del" onclick="return confirm('Excluir?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>