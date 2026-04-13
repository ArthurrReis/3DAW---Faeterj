<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../_css/estilo.css"/>
    <meta charset="UTF-8"/>
    <title>Sistema Questionario - Cadastro</title>
</head>
<body>
<div>
    <?php 
        
        $arquivo_db = "usuario.txt";
        $pagina_atual = "criarusuario.php";
        $msg = "";

        if($_SERVER['REQUEST_METHOD'] == "POST"){
          
            $nome = $_POST["nome"];
            $email = $_POST["email"];

            
            if(!file_exists($arquivo_db)){
                $ponteiro = fopen($arquivo_db, "w");
         
                $header = "Nome;Email\n";
                fwrite($ponteiro, $header);
                fclose($ponteiro);
            }

            $ponteiro = fopen($arquivo_db, "a");
           
            $linha = "{$nome};{$email}\n";
            
            fwrite($ponteiro, $linha);
            fclose($ponteiro);
            $msg = "Usuário cadastrado com sucesso!";
        }
    ?>

    <h1>Cadastro de Usuários - Game Corporativo</h1>

    <form action="<?php echo $pagina_atual; ?>" method="POST">
        Nome Completo: <input type="text" name="nome" required>
        <br><br>    
        E-mail: <input type="email" name="email" required>
        <br><br>
        
        <input type="submit" value="Cadastrar Usuário">
    </form>

    <br>
    <p><strong>Status:</strong> <?php echo $msg; ?></p>
    
    <hr>
    <button><a href="criarpergunta.php">Gerir Perguntas</a></button>
    <button><a href="listarperguntas.php">Ver Perguntas</a></button>
</div>
</body>
</html>