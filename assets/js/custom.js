const loginForm = document.getElementById("login-usuario-form");
const cadForm = document.getElementById("cad-usuario-form");
const cadPedido = document.getElementById("cad-Pedido-form");
const msgAlert = document.getElementById("msgAlert");
const msgAlertErroLogin = document.getElementById("msgAlertErroLogin");
const msgAlertErroCad = document.getElementById("msgAlertErroCad");
const msgAlertErroCadPedido = document.getElementById("msgAlertErroCadPedido");
const loginModal = new bootstrap.Modal(document.getElementById("loginModal"));
const cadModal = new bootstrap.Modal(document.getElementById("cadUsuarioModal"));
const PedidoModal = new bootstrap.Modal(document.getElementById("cadDeclaracaoModal"));

loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    document.getElementById("login-usuario-btn").value = "Validando...";

	var cpf = document.getElementById('cpf').value;

    if (!validaCPF(cpf)) {
        msgAlertErroLogin.innerHTML = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>CPF inválido. Verifique o número digitado<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        document.getElementById('cpf').focus();
    }

    else if (document.getElementById("cpf").value === "") {
        msgAlertErroLogin.innerHTML = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Erro: Necessário preencher o campo CPF!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        document.getElementById('cpf').focus();
    } else if (document.getElementById("senha").value === "") {
        msgAlertErroLogin.innerHTML = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Erro: Necessário preencher o campo Senha!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        document.getElementById('senha').focus();
    } else {
        const dadosForm = new FormData(loginForm);
        const dados = await fetch("validar.php", {
            method: "POST",
            body: dadosForm
        });
        const resposta = await dados.json();
        console.log(resposta);
        if(resposta['erro']){
            msgAlertErroLogin.innerHTML = resposta['msg']
        }else{
            location.reload();
            loginForm.reset();
            loginModal.hide();
            document.getElementById("dados-usuario").innerHTML = "Bem vindo " + resposta['dados'].NomeCliente + 
            "<br><a href='perfil.php'><button type='button' class='btn btn-outline-success'>Fazer Pedido Declaração</button></a>"+
            "<a href='sair.php'><button type='button' class='btn btn-outline-danger'>Sair</button></a><br>";
        }
    }

    document.getElementById("login-usuario-btn").value = "Acessar";
});

cadForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    document.getElementById("cad-usuario-btn").value = "Salvando...";

    const dadosForm = new FormData(cadForm);

    const dados = await fetch("cadastrar.php", {
        method: "POST",
        body: dadosForm 
    });

    const resposta = await dados.json();

    console.log(resposta);

    if(resposta['erro']){
        msgAlertErroCad.innerHTML = resposta['msg'];
    }else{
        cadForm.reset();
        cadModal.hide();
        msgAlert.innerHTML = resposta['msg'];
    }   

    document.getElementById("cad-usuario-btn").value = "Cadastrar";
});

cadPedido.addEventListener("submit", async (e) => {
    e.preventDefault();

    document.getElementById("cad-pedido-btn").value = "Salvando...";

    var select = document.getElementById("cadservico");
    var opcaoValor = select.options[select.selectedIndex].value;
    console.log(opcaoValor);

    if (opcaoValor === '0') {
        msgAlertErroCadPedido.innerHTML = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Erro: Necessário selecionar tipo serviço!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } else {
        msgAlertErroCadPedido.innerHTML = "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Pedido realizado com sucesso !<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    const dadosForm = opcaoValor;

    const dados = await fetch("cadpedido.php", {
         method: "POST",
         body: dadosForm 
    });

    const resposta = await dados.json();
    console.log(resposta);

    if(resposta['erro']){
        msgAlertErroCadPedido.innerHTML = resposta['msg'];
    }else{
        location.reload();
        cadPedido.reset();
        PedidoModal.hide();
        msgAlert.innerHTML = resposta['msg'];
    }   

    document.getElementById("cad-pedido-btn").value = "Cadastrar";
}
});

function validaCPF(cpf) {
    cpf = cpf.replace(/\D+/g, '');
    if (cpf.length !== 11) return false;

    let soma = 0;
    let resto;
    if (/^(\d)\1{10}$/.test(cpf)) return false;

    for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i-1, i)) * (11 - i);
    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;

    soma = 0;
    for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;

    return true;
}
