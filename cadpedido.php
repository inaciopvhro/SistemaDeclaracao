<?php
session_start();
include_once "conexao.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$servico = $dados['cadservico'];
$stservico = '1';
$data = Date('Y');

$query_usuario = "INSERT INTO pedidos (DataAbertura, CodigoCliente, StatusServico) VALUES (now(), :cc, :ss)";
$cad_usuario = $conn->prepare($query_usuario);
$cad_usuario->bindParam(':cc', $_SESSION['id'], PDO::PARAM_INT);
$cad_usuario->bindParam(':ss', $stservico, PDO::PARAM_STR);
$cad_usuario->execute();
if ($cad_usuario->rowCount()) {
    $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Pedido realizado com sucesso!</div>"];
} else {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Pedido não realizado!</div>"];
}

$query_consulta = "INSERT INTO ItensPedido (idPedidos, statusiten, anoexercicio, codigoproduto) VALUES (LAST_INSERT_ID(),'1', $data, $servico)";
$cad_item = $conn->prepare($query_consulta);
$cad_item->execute();


echo json_encode($retorna);
