<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../_css/estilo.css"/>
    <meta charset="UTF-8"/>
    <title>Listagem de Perguntas</title>
</head>
<body>
<div>
    <h1>Perguntas de Texto</h1>
    <?php
    $arquivo_texto = "perguntatexto.txt";
    if (file_exists($arquivo_texto)) {
        $dados = file($arquivo_texto);
        foreach($dados as $linha) {
            if(trim($linha) != "") {
                echo "Pergunta/Resposta: " . str_replace(";", " -> ", htmlspecialchars($linha)) . "<br>";
            }
        }
    } else {
        echo "Nenhuma pergunta de texto cadastrada.";
    }
    ?>

    <hr>

    <h1>Perguntas de Múltipla Escolha</h1>
    <?php
    $arquivo_multipla = "perguntamultipla.txt";
    if (file_exists($arquivo_multipla)) {
        $dados = file($arquivo_multipla);
        foreach($dados as $linha) {
            if(trim($linha) != "") {
                echo "Questão: " . str_replace(";", " | ", htmlspecialchars($linha)) . "<br>";
            }
        }
    } else {
        echo "Nenhuma pergunta de múltipla escolha cadastrada.";
    }
    ?>

    <br><br>
    <button><a href="index.php">Voltar ao Menu</a></button>
</div>
</body>
</html>