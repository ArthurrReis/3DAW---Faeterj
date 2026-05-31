<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $dados = json_decode($input, true);
    
    if ($dados && isset($dados["termo_busca"])) {
        $busca = $dados["termo_busca"];
        $linhas = file("perguntamultipla.txt"); 
        $resultados = [];

        if ($linhas !== false) {
            foreach($linhas as $l){
                $col = explode(";", $l);
                if(stripos($col[0], $busca) !== false){ 
                    $resultados[] = trim($l);
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "resultados" => $resultados]);
        
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Buscar Pergunta</title>
</head>
<body>
    <form id="form-buscar">
        <input type="text" name="termo_busca" placeholder="Digite o termo de busca" required>
        <button type="submit">Buscar</button>
    </form>

    <div id="resultado-busca"></div>

    <script>
        const form = document.getElementById('form-buscar');
        const divResultado = document.getElementById('resultado-busca');

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

                const resposta = await requisicao.json();
                divResultado.innerHTML = '';

                if(resposta.status === 'success') {
                    if(resposta.resultados.length > 0) {
                        resposta.resultados.forEach(item => {
                            const p = document.createElement('p');
                            p.innerText = "Resultado: " + item;
                            divResultado.appendChild(p);
                        });
                    } else {
                        divResultado.innerText = "Nenhum resultado encontrado.";
                    }
                }
            } catch (erro) {
                console.error(erro);
            }
        });
    </script>
</body>
</html>
