function enviarCadastro() {
    const form = document.getElementById('formCadastro');
    const resultadoDiv = document.getElementById('resultado');
    

    if(!form.checkValidity()) {
        form.reportValidity();
        return;
    }

   
    const formData = new FormData(form);

    resultadoDiv.innerHTML = "Processando...";
    resultadoDiv.style.color = "#d4af37";


    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "api/cadastrar.php", true);


    xhttp.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status === 200) {
                try {
            
                    const resposta = JSON.parse(this.responseText);
                    
                    if (resposta.sucesso) {
                        resultadoDiv.innerHTML = resposta.mensagem;
                        resultadoDiv.style.color = "green";
                        form.reset(); 
                    } else {
                        resultadoDiv.innerHTML = resposta.mensagem;
                        resultadoDiv.style.color = "red";
                    }
                } catch (e) {
                    resultadoDiv.innerHTML = "Erro ao ler a resposta do servidor.";
                    resultadoDiv.style.color = "red";
                }
            } else {
                resultadoDiv.innerHTML = "Erro de conexão (Status " + this.status + ").";
                resultadoDiv.style.color = "red";
            }
        }
    };


    xhttp.send(formData);
}

function toggleMenu() {
    const menu = document.getElementById('menuPrincipal');
    if (menu.style.display === 'block') {
        menu.style.display = 'none';
    } else {
        menu.style.display = 'block';
    }
}