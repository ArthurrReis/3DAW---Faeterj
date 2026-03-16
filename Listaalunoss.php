<?php
session_start();


if (!isset($_SESSION['alunos'])) {
    $_SESSION['alunos'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_adicionar'])) {
    $novoAluno = [
        'matricula' => htmlspecialchars($_POST['matricula']),
        'nome'      => htmlspecialchars($_POST['nome']),
        'email'     => htmlspecialchars($_POST['email'])
    ];

    $_SESSION['alunos'][] = $novoAluno;
}

if (isset($_GET['limpar'])) {
    $_SESSION['alunos'] = [];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Alunos</title>
    <style>
        body { font-family: sans-serif; margin: 40px; line-height: 1.6; }
        form { background: #f4f4f4; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        input { display: block; margin-bottom: 10px; padding: 8px; width: 300px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #007BFF; color: white; }
        .btn { padding: 10px 15px; cursor: pointer; border: none; border-radius: 4px; }
        .btn-add { background: #28a745; color: white; }
        .btn-clear { background: #dc3545; color: white; text-decoration: none; font-size: 0.8em; }
    </style>
</head>
<body>

    <h2>Cadastrar Novo Aluno</h2>
    <form method="POST">
        <input type="text" name="matricula" placeholder="Matrícula" required>
        <input type="text" name="nome" placeholder="Nome Completo" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <button type="submit" name="btn_adicionar" class="btn btn-add">Adicionar Aluno</button>
    </form>

    <hr>

    <h2>Listagem de Alunos</h2>
    <table>
        <thead>
            <tr>
                <th>Matrícula</th>
                <th>Nome</th>
                <th>E-mail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($_SESSION['alunos'])): ?>
                <tr>
                    <td colspan="3">Nenhum aluno cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($_SESSION['alunos'] as $aluno): ?>
                    <tr>
                        <td><?= $aluno['matricula'] ?></td>
                        <td><?= $aluno['nome'] ?></td>
                        <td><?= $aluno['email'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="?limpar=true" class="btn btn-clear">Limpar Lista</a>

</body>
</html>