<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $dados = json_decode($input, true);

    if ($dados && isset($dados["pergunta"])) {
        $pergunta = $dados["pergunta"];
        $opA = $dados["opA"];
        $opB = $dados["opB"];
        $opC = $dados["opC"];
        $opD = $dados["opD"];
        $gabarito = $dados["gabarito"];

        $arquivo_db = "perguntamultipla.txt";
        
        if(!file_exists($arquivo_db)){
            $p = fopen($arquivo_db, "w");
            fwrite($p, "Pergunta;OpA;OpB;OpC;OpD;Gabarito\n");
            fclose($p);
        }

        $p = fopen($arquivo_db, "a");
        $linha = "{$pergunta};{$opA};{$opB};{$opC};{$opD};{$gabarito}\n";
        
        fwrite($p, $linha);
        fclose($p);
        
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "mensagem" => "Pergunta de múltipla escolha criada!"]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../_css/estilo.css"/>
    <meta charset="UTF-8"/>
    <title>Questionario - Múltipla Escolha</title>
</head>
<body>
<div>
    <h1>Criar Pergunta de Múltipla Escolha</h1>

    <form id="form-criar">
        Pergunta: <input type="text" name="pergunta" required><br><br>
        
        Opção A: <input type="text" name="opA" required><br>
        Opção B: <input type="text" name="opB" required><br>
        Opção C: <input type="text" name="opC" required><br>
        Opção D: <input type="text" name="opD" required><br><br>
        
        Gabarito (A, B, C ou D): <input type="text" name="gabarito" maxlength="1" required><br><br>
        
        <input type="submit" value="Incluir Pergunta">
    </form>
    <p id="mensagem-retorno"></p>
</div>

<script>
    const form = document.getElementById('form-criar');
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
                pMensagem.innerText = "Erro ao processar a requisição.";
            }
        } catch (erro) {
            console.error(erro);
        }
    });
</script>
</body>
</html>
