<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $dados = json_decode($input, true);
    
    if ($dados && isset($dados["pergunta_old"])) {
        $arquivo_db = "perguntamultipla.txt"; 
        
        $pergunta_original = $dados["pergunta_old"];
        $nova_pergunta = $dados["pergunta"];
        $novas_opcoes = $dados["opcoes"];
        $novo_gabarito = $dados["gabarito"];
        
        $linhas = file($arquivo_db);
        $novo_conteudo = "";
        $alterado = false;

        foreach($linhas as $l){
            $col = explode(";", $l);
            if(trim($col[0]) == $pergunta_original){
                $l = "{$nova_pergunta};{$novas_opcoes};{$novo_gabarito}\n";
                $alterado = true;
            }
            $novo_conteudo .= $l;
        }
        
        if($alterado) {
            file_put_contents($arquivo_db, $novo_conteudo);
            $resposta = ["status" => "success", "mensagem" => "Alterado!"];
        } else {
            $resposta = ["status" => "error", "mensagem" => "Pergunta original não encontrada."];
        }
        
        header('Content-Type: application/json');
        echo json_encode($resposta);
        
        exit; 
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Pergunta</title>
</head>
<body>
    <form id="form-alterar">
        <input type="text" name="pergunta_old" placeholder="Pergunta original" required>
        <input type="text" name="pergunta" placeholder="Nova pergunta" required>
        <input type="text" name="opcoes" placeholder="Novas opções" required>
        <input type="text" name="gabarito" placeholder="Novo gabarito" required>
        <button type="submit">Salvar Alteração</button>
    </form>

    <div id="mensagem-retorno"></div>

    <script>
        const form = document.getElementById('form-alterar');
        const divMensagem = document.getElementById('mensagem-retorno');

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
                    divMensagem.style.color = "green";
                    divMensagem.innerText = resultado.mensagem;
                    form.reset();
                } else {
                    divMensagem.style.color = "red";
                    divMensagem.innerText = resultado.mensagem;
                }

            } catch (erro) {
                console.error(erro);
            }
        });
    </script>
</body>
</html>
