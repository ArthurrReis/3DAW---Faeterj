<?php
session_start();

if (!isset($_SESSION['alunos'])) {
    $_SESSION['alunos'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_salvar'])) {
    $aluno = [
        'matricula' => htmlspecialchars($_POST['matricula']),
        'nome'      => htmlspecialchars($_POST['nome']),
        'email'     => htmlspecialchars($_POST['email'])
    ];

    $index = $_POST['index_edicao'];

    if ($index !== "") {
       
        $_SESSION['alunos'][$index] = $aluno;
    } else {
        
        $_SESSION['alunos'][] = $aluno;
    }
    

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    unset($_SESSION['alunos'][$id]);
    $_SESSION['alunos'] = array_values($_SESSION['alunos']); 
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['limpar'])) {
    $_SESSION['alunos'] = [];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


$alunoEdicao = null;
$indexEdicao = "";

if (isset($_GET['editar'])) {
    $indexEdicao = $_GET['editar'];
    if (isset($_SESSION['alunos'][$indexEdicao])) {
        $alunoEdicao = $_SESSION['alunos'][$indexEdicao];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Alunos</title>
    <style>
        body { font-family: sans-serif; margin: 40px; line-height: 1.6; color: #333; }
        form { background: #f4f4f4; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #ddd; }
        input { display: block; margin-bottom: 10px; padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #007BFF; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn { padding: 8px 12px; cursor: pointer; border: none; border-radius: 4px; text-decoration: none; display: inline-block; font-size: 14px; }
        .btn-add { background: #28a745; color: white; }
        .btn-edit { background: #ffc107; color: #000; margin-right: 5px; }
        .btn-del { background: #dc3545; color: white; }
        .btn-clear { background: #6c757d; color: white; margin-top: 20px; }
    </style>
</head>
<body>

    <h2><?= $alunoEdicao ? "Editar Aluno" : "Cadastrar Novo Aluno" ?></h2>
    
    <form method="POST">
        <input type="hidden" name="index_edicao" value="<?= $indexEdicao ?>">
        
        <input type="text" name="matricula" placeholder="Matrícula" required 
               value="<?= $alunoEdicao ? $alunoEdicao['matricula'] : '' ?>">
        
        <input type="text" name="nome" placeholder="Nome Completo" required 
               value="<?= $alunoEdicao ? $alunoEdicao['nome'] : '' ?>">
        
        <input type="email" name="email" placeholder="E-mail" required 
               value="<?= $alunoEdicao ? $alunoEdicao['email'] : '' ?>">
        
        <button type="submit" name="btn_salvar" class="btn btn-add">
            <?= $alunoEdicao ? "Salvar Alterações" : "Adicionar Aluno" ?>
        </button>
        
        <?php if ($alunoEdicao): ?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn">Cancelar</a>
        <?php endif; ?>
    </form>

    <hr>

    <h2>Listagem de Alunos</h2>
    <table>
        <thead>
            <tr>
                <th>Matrícula</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($_SESSION['alunos'])): ?>
                <tr>
                    <td colspan="4">Nenhum aluno cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($_SESSION['alunos'] as $index => $aluno): ?>
                    <tr>
                        <td><?= $aluno['matricula'] ?></td>
                        <td><?= $aluno['nome'] ?></td>
                        <td><?= $aluno['email'] ?></td>
                        <td>
                            <a href="?editar=<?= $index ?>" class="btn btn-edit">Editar</a>
                            <a href="?excluir=<?= $index ?>" class="btn btn-del" onclick="return confirm('Deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="?limpar=true" class="btn btn-clear">Limpar Toda a Lista</a>

</body>
</html>

