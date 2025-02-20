<?php

include_once "conexao.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados['cadnome'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo nome!</div>"];
} elseif (empty($dados['cpf'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo CPF!</div>"];    
} elseif (empty($dados['cademail'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo Telefone!</div>"];
} elseif (empty($dados['cadsenha'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo senha!</div>"];
} else {

    $query_usuario_pes = "SELECT idCliente FROM Clientes WHERE idcpf=:cpf LIMIT 1";
    $result_usuario = $conn->prepare($query_usuario_pes);
    $result_usuario->bindParam(':cpf', $dados['cpf'], PDO::PARAM_STR);
    $result_usuario->execute();

    if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
        $row_cliente = $result_usuario->fetch(PDO::FETCH_ASSOC);
        $query_usuario = "UPDATE Clientes SET SenhaCliente = :senha, Telefones = :tel WHERE idCliente = :id";
        $cad_usuario = $conn->prepare($query_usuario);
        $senha_cript = password_hash($dados['cadsenha'], PASSWORD_DEFAULT);
        $cad_usuario->bindParam(':senha', $senha_cript, PDO::PARAM_STR);
        $cad_usuario->bindParam(':tel', $dados['cademail'], PDO::PARAM_STR);
        $cad_usuario->bindParam(':id', $row_cliente['idCliente'], PDO::PARAM_INT);
                        
        $cad_usuario->execute();

        if ($cad_usuario->rowCount()) {
            $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Usuário atualizado com sucesso!</div>"];
            
        } else {
            $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Usuário não atualizado com sucesso!</div>"];
        }
    } else {
        $query_usuario = "INSERT INTO Clientes (NomeCliente, idcpf, email, SenhaCliente) VALUES (:nome, :cpf, :email, :senha)";
        $cad_usuario = $conn->prepare($query_usuario);
        $cad_usuario->bindParam(':nome', $dados['cadnome'], PDO::PARAM_STR);
        $cad_usuario->bindParam(':cpf', $dados['cpf'], PDO::PARAM_STR);
        $cad_usuario->bindParam(':email', $dados['cademail'], PDO::PARAM_STR);
        $senha_cript = password_hash($dados['cadsenha'], PASSWORD_DEFAULT);
        $cad_usuario->bindParam(':senha', $senha_cript, PDO::PARAM_STR);
                
        $cad_usuario->execute();

        if ($cad_usuario->rowCount()) {
            $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Usuário cadastrado com sucesso!</div>"];
            
        } else {
            $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Usuário não cadastrado com sucesso!</div>"];
        }
    }
}

echo json_encode($retorna);
