<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../_css/estilo.css"/>
    <meta charset="UTF-8"/>
    <title>Questionario - Múltipla Escolha</title>
</head>
<body>
<div>
    <?php 
        $msg = "";
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $pergunta = $_POST["pergunta"];
            $opA = $_POST["opA"];
            $opB = $_POST["opB"];
            $opC = $_POST["opC"];
            $opD = $_POST["opD"];
            $gabarito = $_POST["gabarito"];

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
            $msg = "Pergunta de múltipla escolha criada!";
        }
    ?>

    <h1>Criar Pergunta de Múltipla Escolha</h1>

    <form action="criarperguntamultipla.php" method="POST">
        Pergunta: <input type="text" name="pergunta" required><br><br>
        
        Opção A: <input type="text" name="opA" required><br>
        Opção B: <input type="text" name="opB" required><br>
        Opção C: <input type="text" name="opC" required><br>
        Opção D: <input type="text" name="opD" required><br><br>
        
        Gabarito (A, B, C ou D): <input type="text" name="gabarito" maxlength="1" required><br><br>
        
        <input type="submit" value="Incluir Pergunta">
    </form>
    <p><?php echo $msg; ?></p>
</div>
</body>
</html>