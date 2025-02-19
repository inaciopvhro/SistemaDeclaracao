const loginForm = document.getElementById("login-usuario-form");
const msgAlert = document.getElementById("msgAlert");
const msgAlertErroLogin = document.getElementById("msgAlertErroLogin");
const msgAlertErroCad = document.getElementById("msgAlertErroCad");
const loginModal = new bootstrap.Modal(document.getElementById("loginModal"));

loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    
    document.getElementById("login-usuario-btn").value = "Validando...";

    if (document.getElementById("email").value === "") {
        msgAlertErroLogin.innerHTML = "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo CPF!</div>";
    } else if (document.getElementById("senha").value === "") {
        msgAlertErroLogin.innerHTML = "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo senha!</div>";
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
            loginForm.reset();
            loginModal.hide();
            document.getElementById("dados-usuario").innerHTML = "Bem vindo " + resposta['dados'].NomeCliente + "<br><a href='perfil.php'>Perfil</a> - <a href='listar.php'>Minhas Mensagens</a> - <a href='sair.php'>Sair</a><br>";
            
        }
    }

    document.getElementById("login-usuario-btn").value = "Acessar";
});