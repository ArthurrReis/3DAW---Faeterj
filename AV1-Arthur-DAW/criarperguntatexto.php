<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../_css/estilo.css"/>
    <meta charset="UTF-8"/>
    <title>Questionario - Criar Pergunta Texto</title>
</head>
<body>
<div>
    <?php 
        $arquivo_db = "perguntatexto.txt";
        $pagina_atual = "criarperguntaTexto.php";
        $msg = "";

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $pergunta = $_POST["pergunta"];
            $resposta = $_POST["resposta"];

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
            $msg = "Pergunta de texto criada com sucesso!";
        }
    ?>

    <h1>Criar Pergunta de Texto</h1>

    <form action="<?php echo $pagina_atual; ?>" method="POST">
        
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
    
    <p><strong>Status:</strong> <?php echo $msg; ?></p>
</div>
</body>
</html>