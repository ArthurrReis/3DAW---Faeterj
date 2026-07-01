/* ============================================================
   STUDIO V - app.js
   Guarda a seleção de serviço/profissional/data/hora no
   sessionStorage do navegador enquanto o cliente navega.
   Comunicação via XMLHttpRequest (XHR) + JSON exigido na AV2.
============================================================ */

// ---------- Utilidades de agendamento (sessionStorage) ----------
function getAgendamento() {
    const raw = sessionStorage.getItem('agendamento');
    return raw ? JSON.parse(raw) : {};
}
function salvarAgendamento(dados) {
    const atual = getAgendamento();
    sessionStorage.setItem('agendamento', JSON.stringify({ ...atual, ...dados }));
}

// ---------- LOGIN / SESSÃO ----------
function abrirLogin() {
    verificarSessao().then(sessao => {
        if (sessao.logado) {
            if (confirm(`Olá, ${sessao.nome}! Deseja sair da sua conta?`)) {
                logout();
            }
        } else {
            const modal = document.getElementById('modalLogin');
            if (modal) modal.style.display = 'block';
        }
    });
}

function fecharLogin() {
    const modal = document.getElementById('modalLogin');
    if (modal) modal.style.display = 'none';
}

window.onclick = function (event) {
    const modal = document.getElementById('modalLogin');
    if (modal && event.target == modal) modal.style.display = 'none';
};

function verificarSessao() {
    // Usando Promise para manter a estrutura do .then(), mas operando com XMLHttpRequest por baixo
    return new Promise((resolve) => {
        const xhttp = new XMLHttpRequest();
        xhttp.open("GET", "api/sessao.php", true);
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    try {
                        resolve(JSON.parse(this.responseText));
                    } catch (e) {
                        resolve({ logado: false });
                    }
                } else {
                    resolve({ logado: false });
                }
            }
        };
        xhttp.send();
    });
}

function atualizarHeaderLogin() {
    const icon = document.querySelector('.menu-icon');
    if (!icon) return;
    verificarSessao().then(sessao => {
        if (sessao.logado) {
            icon.innerHTML = '👤';
            icon.title = `Olá, ${sessao.nome} — clique para sair`;
        } else {
            icon.innerHTML = '&#9776;';
            icon.title = 'Entrar';
        }
    });
}

function fazerLogin() {
    const cpf = document.getElementById('loginCpf').value.trim();
    const senha = document.getElementById('loginSenha').value;
    const msg = document.getElementById('loginMensagem');
    if(msg) msg.textContent = '';

    if (!cpf || !senha) {
        if(msg) msg.textContent = 'Preencha CPF e senha.';
        return;
    }

    const dados = new FormData();
    dados.append('cpf', cpf);
    dados.append('senha', senha);

    // Requisição XMLHttpRequest Clássica
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "api/login.php", true);
    
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                try {
                    const resp = JSON.parse(this.responseText);
                    if (resp.sucesso) {
                        fecharLogin();
                        location.reload();
                    } else {
                        if(msg) msg.textContent = resp.mensagem;
                    }
                } catch (e) {
                    if(msg) msg.textContent = 'Erro ao ler a resposta do servidor.';
                }
            } else {
                if(msg) msg.textContent = 'Erro de conexão.';
            }
        }
    };
    xhttp.send(dados);
}

function logout() {
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "api/logout.php", true);
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            location.href = 'index.html';
        }
    };
    xhttp.send();
}

// ---------- CADASTRO ----------
function enviarCadastro() {
    const form = document.getElementById('formCadastro');
    const resultadoDiv = document.getElementById('resultado');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('confirmarSenha').value;
    if (senha !== confirmarSenha) {
        resultadoDiv.textContent = 'As senhas não coincidem.';
        resultadoDiv.style.color = 'red';
        return;
    }

    const formData = new FormData(form);
    resultadoDiv.textContent = 'Processando...';
    resultadoDiv.style.color = '#d4af37';

    // Requisição XMLHttpRequest Clássica
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "api/cadastrar.php", true);
    
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                try {
                    const resposta = JSON.parse(this.responseText);
                    resultadoDiv.textContent = resposta.mensagem;
                    resultadoDiv.style.color = resposta.sucesso ? 'green' : 'red';
                    
                    if (resposta.sucesso) {
                        form.reset();
                        setTimeout(() => location.href = 'index.html', 1500);
                    }
                } catch (e) {
                    resultadoDiv.textContent = 'Erro ao processar reposta.';
                    resultadoDiv.style.color = 'red';
                }
            } else {
                resultadoDiv.textContent = 'Erro de conexão com o servidor.';
                resultadoDiv.style.color = 'red';
            }
        }
    };
    xhttp.send(formData);
}

// ---------- AGENDAR (agendar.html) ----------
function expandirProfissionais(elemento) {
    document.querySelectorAll('.profissionais-opcoes').forEach(el => el.style.display = 'none');
    const opcoes = elemento.querySelector('.profissionais-opcoes');
    if (opcoes) opcoes.style.display = 'block';
}

function escolherProfissional(botao) {
    const item = botao.closest('.service-item');
    salvarAgendamento({
        servico_id: item.dataset.id,
        servico_nome: item.querySelector('h4').textContent.trim(),
        valor: item.dataset.valor,
        duracao: item.dataset.duracao,
        profissional: botao.textContent.trim()
    });
    window.location.href = 'calendario.html';
}

// ---------- CALENDÁRIO (calendario.html) ----------
function inicializarCalendario() {
    const cont = document.getElementById('calendarioContainer');
    if (!cont) return;

    const agendamento = getAgendamento();
    if (!agendamento.servico_nome) {
        location.href = 'agendar.html';
        return;
    }

    document.getElementById('resumoServico').textContent =
        `${agendamento.servico_nome} — ${agendamento.profissional} — R$ ${Number(agendamento.valor).toFixed(2)}`;

    renderizarCalendario(new Date());
}

function renderizarCalendario(refDate) {
    const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
        'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    const titulo = document.getElementById('mesAtual');
    const tbody = document.getElementById('diasCalendario');
    tbody.innerHTML = '';
    titulo.textContent = `${meses[refDate.getMonth()]} ${refDate.getFullYear()}`;

    const primeiroDia = new Date(refDate.getFullYear(), refDate.getMonth(), 1);
    const ultimoDia = new Date(refDate.getFullYear(), refDate.getMonth() + 1, 0);
    const hoje = new Date();
    hoje.setHours(0, 0, 0, 0);

    let linha = document.createElement('tr');
    for (let i = 0; i < primeiroDia.getDay(); i++) linha.appendChild(document.createElement('td'));

    for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
        const dataAtual = new Date(refDate.getFullYear(), refDate.getMonth(), dia);
        const td = document.createElement('td');
        td.textContent = String(dia).padStart(2, '0');

        if (dataAtual < hoje) {
            td.classList.add('dia-passado');
        } else {
            td.addEventListener('click', () => mostrarHorarios(dataAtual, td));
        }

        linha.appendChild(td);

        if (dataAtual.getDay() === 6) {
            tbody.appendChild(linha);
            linha = document.createElement('tr');
        }
    }
    if (linha.children.length) tbody.appendChild(linha);
}

function mostrarHorarios(data, tdClicado) {
    document.querySelectorAll('#diasCalendario td').forEach(td => td.classList.remove('dia-selecionado'));
    if (tdClicado) tdClicado.classList.add('dia-selecionado');

    const dataISO = `${data.getFullYear()}-${String(data.getMonth() + 1).padStart(2, '0')}-${String(data.getDate()).padStart(2, '0')}`;
    const agendamento = getAgendamento();
    const div = document.getElementById('horariosDisponiveis');
    div.style.display = 'block';
    const grid = document.querySelector('.grid-horarios');
    grid.innerHTML = 'Carregando horários...';

    // Requisição XMLHttpRequest Clássica (GET com parâmetros na URL)
    const xhttp = new XMLHttpRequest();
    const url = `api/horarios.php?data=${dataISO}&profissional=${encodeURIComponent(agendamento.profissional || '')}`;
    xhttp.open("GET", url, true);
    
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                try {
                    const resp = JSON.parse(this.responseText);
                    grid.innerHTML = '';
                    const todosHorarios = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                        '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
                        '17:00', '17:30', '18:00', '18:30'];

                    todosHorarios.forEach(hora => {
                        const btn = document.createElement('button');
                        btn.className = 'btn-horario';
                        btn.textContent = hora;
                        
                        if (resp.ocupados && resp.ocupados.includes(hora)) {
                            btn.disabled = true;
                            btn.classList.add('horario-ocupado');
                        } else {
                            btn.onclick = () => escolherHorario(dataISO, hora);
                        }
                        grid.appendChild(btn);
                    });
                } catch (e) {
                    grid.innerHTML = 'Erro ao processar horários do servidor.';
                }
            } else {
                grid.innerHTML = 'Erro ao carregar horários.';
            }
        }
    };
    xhttp.send();
}

function escolherHorario(dataISO, hora) {
    salvarAgendamento({ data: dataISO, hora: hora });
    window.location.href = 'pagamento.html';
}

// ---------- PAGAMENTO (pagamento.html) ----------
function inicializarPagamento() {
    const painel = document.getElementById('resumoPagamento');
    if (!painel) return;

    const ag = getAgendamento();
    if (!ag.data || !ag.hora) {
        location.href = 'agendar.html';
        return;
    }

    const [ano, mes, dia] = ag.data.split('-');
    document.getElementById('resumoTexto').innerHTML = `
        <p>Você está agendando: <strong>${ag.servico_nome}</strong></p>
        <p>Profissional: ${ag.profissional}</p>
        <p>Data e Hora: ${dia}/${mes}/${ano} - ${ag.hora}</p>`;
    document.getElementById('resumoValor').textContent = `R$ ${Number(ag.valor).toFixed(2)}`;

    verificarSessao().then(sessao => {
        if (!sessao.logado) {
            alert('Você precisa entrar na sua conta para finalizar o agendamento.');
            location.href = 'index.html';
        }
    });
}

function confirmarPagamento() {
    const termos = document.getElementById('aceitarTermos');
    if (!termos.checked) {
        alert('Você precisa aceitar os termos para continuar.');
        return;
    }

    const ag = getAgendamento();
    const formaPagamento = document.getElementById('formaPagamento').value;
    const btn = document.getElementById('btnPagar');
    btn.disabled = true;
    btn.textContent = 'Processando...';

    const dados = new FormData();
    dados.append('servico_id', ag.servico_id);
    dados.append('profissional', ag.profissional);
    dados.append('data', ag.data);
    dados.append('hora', ag.hora);
    dados.append('forma_pagamento', formaPagamento);

    // Requisição XMLHttpRequest Clássica
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "api/agendar.php", true);
    
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                try {
                    const resp = JSON.parse(this.responseText);
                    if (resp.sucesso) {
                        sessionStorage.removeItem('agendamento');
                        alert('Agendamento confirmado com sucesso!');
                        location.href = 'index.html';
                    } else {
                        alert(resp.mensagem);
                        btn.disabled = false;
                        btn.textContent = 'Pagar';
                    }
                } catch (e) {
                    alert('Erro inesperado na resposta do servidor.');
                    btn.disabled = false;
                    btn.textContent = 'Pagar';
                }
            } else {
                alert('Erro de conexão.');
                btn.disabled = false;
                btn.textContent = 'Pagar';
            }
        }
    };
    xhttp.send(dados);
}

// ---------- INICIALIZAÇÃO ----------
document.addEventListener('DOMContentLoaded', () => {
    atualizarHeaderLogin();
    inicializarCalendario();
    inicializarPagamento();
});