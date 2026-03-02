<?php
    $resultado = ""; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $a = $_POST["a"];
        $b = $_POST["b"];
        $operacao = $_POST["operacao"];

       
        if ($operacao == "somar") {
            $resultado = $a + $b;
        } elseif ($operacao == "subtrair") {
            $resultado = $a - $b;
        } elseif ($operacao == "multiplicar") {
            $resultado = $a * $b;
        } elseif ($operacao == "dividir") {
           
        } 
    }
?>
<!DOCTYPE html>
<html>
<body>
    <h1><?php echo 'Minha Calculadora';?></h1>

    <form method='POST' action='calculadora.php'>
        a: <input type="text" name="a"><br><br>
        
        Operação:
        <select name="operacao">
            <option value="somar">Somar (+)</option>
            <option value="subtrair">Subtrair (-)</option>
            <option value="multiplicar">Multiplicar (*)</option>
            <option value="dividir">Dividir (/)</option>
        </select><br><br>

        b: <input type="text" name="b"><br><br>
        
        <input type="submit" value="Calcular">
        <br><br>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo 'Resultado: ' . $resultado; 
        }
        ?>
    </form>
</body>
</html>