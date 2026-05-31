<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $dados = json_decode($input, true);

    if ($dados && isset($dados["pergunta"])) {
        $pergunta = $dados["pergunta"];
        $resposta = $dados["resposta"];
        $arquivo_db = "perguntatexto.txt";

        if(!file_exists($arquivo_db)){
            $ponteiro = fopen($arquivo_db, "w");
            $header = "pergunta;resposta\n";
            fwrite($ponteiro, $header);
            fclose($ponteiro);
        }

        $ponteiro = fopen($arquivo_db, "a");
        $linha = "{$pergunta};{$resposta}\n";
        
        fwrite($ponteiro, $linha);
        fclose($ponteiro);
        
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "mensagem" => "Pergunta de texto criada com sucesso!"]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../_css/estilo.css"/>
    <meta charset="UTF-8"/>
    <title>Questionario - Criar Pergunta Texto</title>
</head>
<body>
<div>
    <h1>Criar Pergunta de Texto</h1>

    <form id="form-criar-texto">
        Pergunta: 
        <br>
        <textarea name="pergunta" rows="4" cols="50" required></textarea>
        <br><br>
        
        Resposta Esperada: 
        <br>
        <input type="text" name="resposta" required size="50">
        <br><br>
        
        <input type="submit" value="Incluir Pergunta de Texto">
    </form>

    <br>
    <button><a href="index.php">Voltar ao Menu</a></button>
    <button><a href="listarperguntas.php">Listar Perguntas</a></button>
    
    <p><strong>Status:</strong> <span id="status-mensagem"></span></p>
</div>

<script>
    const form = document.getElementById('form-criar-texto');
    const spanStatus = document.getElementById('status-mensagem');

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
                spanStatus.style.color = "green";
                spanStatus.innerText = resultado.mensagem;
                form.reset();
            } else {
                spanStatus.style.color = "red";
                spanStatus.innerText = "Erro ao processar a requisição.";
            }
        } catch (erro) {
            console.error(erro);
        }
    });
</script>
</body>
</html>
