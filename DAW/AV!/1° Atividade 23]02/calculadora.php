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
            $resultado = ($b != 0) ? ($a / $b) : "Erro (divisão por zero)";
        } elseif ($operacao == "potencia") {
            $resultado = pow($a, $b);
        } elseif ($operacao == "raiz") {
            $resultado = sqrt($a);
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Calculadora PHP</title>
</head>
<body>
    <h1>Minha Calculadora</h1>

    <form method='POST' action=''>
        a: <input type="number" step="any" name="a" required><br><br>
        
        Operação:
        <select name="operacao">
            <option value="somar">Somar (+)</option>
            <option value="subtrair">Subtrair (-)</option>
            <option value="multiplicar">Multiplicar (*)</option>
            <option value="dividir">Dividir (/)</option>
            <option value="potencia">Potência (^)</option>
            <option value="raiz">Raiz Quadrada de 'a' (√)</option>
        </select><br><br>

        b: <input type="number" step="any" name="b"><br><br>
        
        <input type="submit" value="Calcular">
        <br><br>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<strong>Resultado: $resultado</strong>"; 
        }
        ?>
    </form>
</body>
</html>
