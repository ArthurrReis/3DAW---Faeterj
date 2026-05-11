<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Calculadora JS</title>
</head>
<body>
    <h1>Minha Calculadora JS</h1>

    <form id="formCalculadora">
        a: <input type="number" step="any" id="a" required><br><br>
        
        Operação:
        <select id="operacao">
            <option value="somar">Somar (+)</option>
            <option value="subtrair">Subtrair (-)</option>
            <option value="multiplicar">Multiplicar (*)</option>
            <option value="dividir">Dividir (/)</option>
            <option value="potencia">Potência (^)</option>
            <option value="raiz">Raiz Quadrada de 'a' (√)</option>
        </select><br><br>

        b: <input type="number" step="any" id="b"><br><br>
        
        <input type="submit" value="Calcular">
    </form>
    
    <br>
  
    <div id="resultado"></div>

    <script>
        const form = document.getElementById('formCalculadora');

        form.addEventListener('submit', function(evento) {
            evento.preventDefault(); 

            let a = parseFloat(document.getElementById('a').value);
            let operacao = document.getElementById('operacao').value;
            
            let bInput = document.getElementById('b').value;
            let b = bInput !== "" ? parseFloat(bInput) : 0; 
            
            let resultado = "";

            if (operacao === "somar") {
                resultado = a + b;
            } else if (operacao === "subtrair") {
                resultado = a - b;
            } else if (operacao === "multiplicar") {
                resultado = a * b;
            } else if (operacao === "dividir") {
                resultado = (b !== 0) ? (a / b) : "Erro (divisão por zero)";
            } else if (operacao === "potencia") {
                resultado = Math.pow(a, b); 
            } else if (operacao === "raiz") {
                resultado = Math.sqrt(a);
            }

            document.getElementById('resultado').innerHTML = "<strong>Resultado: " + resultado + "</strong>";
        });
    </script>
</body>
</html>
