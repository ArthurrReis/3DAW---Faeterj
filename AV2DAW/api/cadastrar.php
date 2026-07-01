<?php
header('Content-Type: application/json; charset=utf-8');
require 'conexao.php';

$resposta = ['sucesso' => false, 'mensagem' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $resposta['mensagem'] = 'Método não permitido.';
    echo json_encode($resposta);
    exit;
}

$nome        = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$cpf         = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : '';
$nascimento  = isset($_POST['nascimento']) ? trim($_POST['nascimento']) : '';
$cartao      = isset($_POST['cartao']) ? preg_replace('/\D/', '', $_POST['cartao']) : '';
$senha       = isset($_POST['senha']) ? $_POST['senha'] : '';

if (empty($nome) || empty($cpf) || empty($nascimento) || empty($cartao) || empty($senha)) {
    $resposta['mensagem'] = 'Todos os campos são obrigatórios.';
    echo json_encode($resposta);
    exit;
}

if (strlen($cpf) !== 11) {
    $resposta['mensagem'] = 'CPF inválido. Digite os 11 números.';
    echo json_encode($resposta);
    exit;
}

if (strlen($senha) < 6) {
    $resposta['mensagem'] = 'A senha deve ter pelo menos 6 caracteres.';
    echo json_encode($resposta);
    exit;
}

if (strlen($cartao) < 13) {
    $resposta['mensagem'] = 'Número de cartão inválido.';
    echo json_encode($resposta);
    exit;
}

// Nunca guardamos o número completo do cartão. Só os 4 últimos dígitos,
// apenas para exibirmos algo como "final 1234" mais tarde.
$ultimosDigitos = substr($cartao, -4);
$bandeira = 'Outra';
if (preg_match('/^4/', $cartao)) $bandeira = 'Visa';
elseif (preg_match('/^5[1-5]/', $cartao)) $bandeira = 'Mastercard';
elseif (preg_match('/^3[47]/', $cartao)) $bandeira = 'American Express';

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO clientes (nome_completo, cpf, data_nascimento, senha_hash) VALUES (:nome, :cpf, :nascimento, :senha)");
    $stmt->execute([
        ':nome' => $nome,
        ':cpf' => $cpf,
        ':nascimento' => $nascimento,
        ':senha' => $senhaHash
    ]);
    $clienteId = $pdo->lastInsertId();

    $stmt2 = $pdo->prepare("INSERT INTO cartoes (cliente_id, ultimos_digitos, bandeira) VALUES (:cliente_id, :ultimos, :bandeira)");
    $stmt2->execute([
        ':cliente_id' => $clienteId,
        ':ultimos' => $ultimosDigitos,
        ':bandeira' => $bandeira
    ]);

    $pdo->commit();
    $resposta['sucesso'] = true;
    $resposta['mensagem'] = 'Cadastro realizado com sucesso! Faça login para agendar.';
} catch (PDOException $e) {
    $pdo->rollBack();
    if ($e->getCode() == 23000) {
        $resposta['mensagem'] = 'Este CPF já está cadastrado.';
    } else {
        $resposta['mensagem'] = 'Erro ao cadastrar. Tente novamente.';
    }
}

echo json_encode($resposta);