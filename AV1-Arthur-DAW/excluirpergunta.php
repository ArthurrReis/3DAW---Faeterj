<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $dados = json_decode($input, true);

    if ($dados && isset($dados["pergunta"])) {
        $arquivo = "perguntamultipla.txt";
        $pergunta_deletar = $dados["pergunta"];

        $linhas = file($arquivo);
        $final = "";
        $deletado = false;

        if ($linhas !== false) {
            foreach($linhas as $l){
                $col = explode(";", $l);
                if(trim($col[0]) != $pergunta_deletar){
                    $final .= $l;
                } else {
                    $deletado = true;
                }
            }
            file_put_contents($arquivo, $final);
        }
        
        header('Content-Type: application/json');
        if ($deletado) {
            echo json_encode(["status" => "success", "mensagem" => "Pergunta deletada com sucesso!"]);
        } else {
            echo json_encode(["status" => "error", "mensagem" => "Pergunta não encontrada."]);
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="../_css/estilo.css"/>
    <meta charset="UTF-8">
    <title>Excluir Pergunta</title>
</head>
<body>
    <div>
        <h1>Excluir Pergunta</h1>
        <form id="form-deletar">
            Pergunta a ser deletada: <input type="text" name="pergunta" required>
            <button type="submit">Deletar</button>
        </form>
        <p id="mensagem-retorno"></p>
    </div>

    <script>
        const form = document.getElementById('form-deletar');
        const pMensagem = document.getElementById('mensagem-retorno');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(form);
            const dadosJson = Object.fromEntries(formData.entries());

            try {
                const requisicao = await fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dadosJson)
                });

                const resultado = await requisicao.json();

                if(resultado.status === 'success') {
                    pMensagem.style.color = "green";
                    pMensagem.innerText = resultado.mensagem;
                    form.reset();
                } else {
                    pMensagem.style.color = "red";
                    pMensagem.innerText = resultado.mensagem;
                }
            } catch (erro) {
                console.error(erro);
            }
        });
    </script>
</body>
</html>
