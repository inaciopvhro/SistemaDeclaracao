<?php
session_start();
include_once "conexao.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


    $query_usuario = "SELECT idCliente, NomeCliente, idcpf, SenhaCliente
                FROM Clientes
                WHERE idcpf=:email
                LIMIT 1";
    $result_usuario = $conn->prepare($query_usuario);
    $result_usuario->bindParam(':email', $dados['cpf'], PDO::PARAM_STR);
    $result_usuario->execute();

    if(($result_usuario) and ($result_usuario->rowCount() != 0)){
                
        $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
        $retorna = ['erro'=> false, 'dados' => $row_usuario];
        if(password_verify($dados['senha'], $row_usuario['SenhaCliente'])){
            $_SESSION['id'] =  $row_usuario['idCliente'];
            $_SESSION['nome'] =  $row_usuario['NomeCliente'];
            $_SESSION['cpf'] =  $row_usuario['idcpf'];
            
            $retorna = ['erro'=> false, 'dados' => $row_usuario];
        }else{
            $retorna = ['erro'=> true, 'msg' => "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Erro: login ou senha inválida!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"];
        }     
    }else{
        $retorna = ['erro'=> true, 'msg' => "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Erro: login ou senha inválida!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"];
    }      
    

echo json_encode($retorna);