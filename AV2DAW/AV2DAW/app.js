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
                    resultadoDiv.innerHTML = "Erro ao processar a resposta.";
                    resultadoDiv.style.color = "red";
                }
            } else {
                resultadoDiv.innerHTML = "Erro de conexão.";
                resultadoDiv.style.color = "red";
            }
        }
    };
    xhttp.send(formData);
}

function abrirLogin() {
    document.getElementById("modalLogin").style.display = "block";
}

function fecharLogin() {
    document.getElementById("modalLogin").style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById("modalLogin");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function expandirProfissionais(elemento) {
    let todos = document.querySelectorAll('.profissionais-opcoes');
    todos.forEach(el => el.style.display = 'none');

    let opcoes = elemento.querySelector('.profissionais-opcoes');
    if(opcoes) opcoes.style.display = 'block';
}

function escolherProfissional() {
    window.location.href = 'calendario.html';
}

function mostrarHorarios(dia) {
    document.getElementById('horariosDisponiveis').style.display = 'block';
}

function escolherHorario() {
    window.location.href = 'pagamento.html';
}