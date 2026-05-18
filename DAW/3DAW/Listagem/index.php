<?php 
require_once 'funçoes.php';
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
        
        input[type="text"], input[type="email"] {
            padding: 6px;
            width: 280px;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: block;
            margin-bottom: 4px;
        }
        input.campo-erro {
            border-color: red;
        }
        .erro { color: red; font-size: 0.85em; margin: 2px 0 12px 0; display: none; }
        .campo { margin-bottom: 12px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .btn-edit { background: #ffc107; color: black; padding: 5px; text-decoration: none; }
        .btn-del { background: #dc3545; color: white; padding: 5px; text-decoration: none; }
    </style>
</head>
<body>

    <h2><?= $alunoEdicao ? "Editar Aluno" : "Novo Aluno" ?></h2>
    
    <form id="formAluno" action="processar.php" method="POST" onsubmit="return validarFormulario()">
        <input type="hidden" name="index_edicao" id="index_edicao" value="<?= $indexEdicao ?>">
        
        <div class="campo">
            <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" maxlength="10" value="<?= $alunoEdicao['matricula'] ?? '' ?>">
            <p class="erro" id="erroMatricula"></p>
        </div>

        <div class="campo">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" maxlength="100" value="<?= $alunoEdicao['nome'] ?? '' ?>">
            <p class="erro" id="erroNome"></p>
        </div>

        <div class="campo">
            <label for="email">E-mail:</label>
            <input type="text" id="email" name="email" maxlength="150" value="<?= $alunoEdicao['email'] ?? '' ?>">
            <p class="erro" id="erroEmail"></p>
        </div>

        <button type="submit"><?= $alunoEdicao ? "Atualizar" : "Cadastrar" ?></button>
        <?php if ($alunoEdicao): ?>
            <a href="index.php" style="margin-left: 10px;">Cancelar</a>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>Matrícula</th><th>Nome</th><th>E-mail</th><th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($alunos)): ?>
                <tr>
                    <td colspan="4">Nenhum aluno cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($alunos as $index => $aluno): ?>
                <tr>
                    <td><?= htmlspecialchars($aluno['matricula']) ?></td>
                    <td><?= htmlspecialchars($aluno['nome']) ?></td>
                    <td><?= htmlspecialchars($aluno['email']) ?></td>
                    <td>
                        <a href="index.php?editar=<?= $index ?>" class="btn-edit">Editar</a>
                        <a href="excluir.php?id=<?= $index ?>" class="btn-del" onclick="return confirm('Excluir?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

<script>
    function mostrarErro(idCampo, idErro, mensagem) {
        document.getElementById(idCampo).classList.add("campo-erro");
        var spanErro = document.getElementById(idErro);
        spanErro.textContent = "⚠ " + mensagem;
        spanErro.style.display = "block";
    }

    function limparErro(idCampo, idErro) {
        document.getElementById(idCampo).classList.remove("campo-erro");
        var spanErro = document.getElementById(idErro);
        spanErro.textContent = "";
        spanErro.style.display = "none";
    }

    document.getElementById("matricula").addEventListener("blur", function () {
        validarMatricula();
    });

    document.getElementById("nome").addEventListener("blur", function () {
        validarNome();
    });

    document.getElementById("email").addEventListener("blur", function () {
        validarEmail();
    });

    function validarMatricula() {
        var valor = document.getElementById("matricula").value.trim();

        if (valor === "") {
            mostrarErro("matricula", "erroMatricula", "A matrícula é obrigatória.");
            return false;
        }
        if (!/^\d+$/.test(valor)) {
            mostrarErro("matricula", "erroMatricula", "A matrícula deve conter apenas números.");
            return false;
        }
        if (valor.length < 4 || valor.length > 10) {
            mostrarErro("matricula", "erroMatricula", "A matrícula deve ter entre 4 e 10 dígitos.");
            return false;
        }

        limparErro("matricula", "erroMatricula");
        return true;
    }
    
    function validarNome() {
        var valor = document.getElementById("nome").value.trim();

        if (valor === "") {
            mostrarErro("nome", "erroNome", "O nome é obrigatório.");
            return false;
        }
        if (valor.length < 3) {
            mostrarErro("nome", "erroNome", "O nome deve ter pelo menos 3 caracteres.");
            return false;
        }
        if (valor.length > 100) {
            mostrarErro("nome", "erroNome", "O nome deve ter no máximo 100 caracteres.");
            return false;
        }
        if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(valor)) {
            mostrarErro("nome", "erroNome", "O nome deve conter apenas letras e espaços.");
            return false;
        }

        limparErro("nome", "erroNome");
        return true;
    }

    function validarEmail() {
        var valor = document.getElementById("email").value.trim();

        if (valor === "") {
            mostrarErro("email", "erroEmail", "O e-mail é obrigatório.");
            return false;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)) {
            mostrarErro("email", "erroEmail", "Informe um e-mail válido (ex: aluno@email.com).");
            return false;
        }

        limparErro("email", "erroEmail");
        return true;
    }

    function validarFormulario() {
        var matriculaOk = validarMatricula();
        var nomeOk      = validarNome();
        var emailOk     = validarEmail();

        return matriculaOk && nomeOk && emailOk;
    }
</script>

</body>
</html>
