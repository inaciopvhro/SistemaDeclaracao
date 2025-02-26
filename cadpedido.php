<?php
session_start();
include_once "conexao.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$data = Date('Y');
$servico = $dados['cadservico'];
if ($servico === '1') {
    $descservico = "Declaração Simplificada Ano: $data";
} else {
    $descservico = "Declaração Completa Ano: $data";
}
$stservico = "1";

$query_pedido = "SELECT idPedidos, CodigoCliente, StatusServico FROM pedidos WHERE CodigoCliente=:c AND StatusServico='1' LIMIT 1";
$result_pedido = $conn->prepare($query_pedido);
$result_pedido->bindParam(':c', $_SESSION['id'], PDO::PARAM_STR);
$result_pedido->execute();

if (($result_pedido) and ($result_pedido->rowCount() != 0)) {
    $row_pedido = $result_pedido->fetch(PDO::FETCH_ASSOC);
    $query_consulta = "INSERT INTO ItensPedido (idPedidos, statusiten, anoexercicio, codigoproduto, descitem) VALUES (:id, :s, :d, :p, :i)";
    $cad_item = $conn->prepare($query_consulta);
    $cad_item->bindParam(':id', $row_pedido['idPedidos'], PDO::PARAM_INT);
    $cad_item->bindParam(':s', $stservico, PDO::PARAM_STR);
    $cad_item->bindParam(':d', $data, PDO::PARAM_STR);
    $cad_item->bindParam(':p', $servico, PDO::PARAM_INT);
    $cad_item->bindParam(':i', $descservico, PDO::PARAM_STR);
    $cad_item->execute();
    if ($cad_item->rowCount()) {
        $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Pedido realizado com sucesso!</div>"];
    } else {
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Pedido não realizado!</div>"];
    }    
} else {

$query_usuario = "INSERT INTO pedidos (DataAbertura, CodigoCliente, StatusServico, statusonline) VALUES (now(), :cc, :ss, '0')";
$cad_usuario = $conn->prepare($query_usuario);
$cad_usuario->bindParam(':cc', $_SESSION['id'], PDO::PARAM_INT);
$cad_usuario->bindParam(':ss', $stservico, PDO::PARAM_STR);
$cad_usuario->execute();
if ($cad_usuario->rowCount()) {
    $retorna = ['erro' => false, 'msg' => "<div class='alert alert-success' role='alert'>Pedido realizado com sucesso!</div>"];
} else {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Pedido não realizado!</div>"];
}

$query_consulta = "INSERT INTO ItensPedido (idPedidos, statusiten, anoexercicio, codigoproduto, descitem) VALUES (LAST_INSERT_ID(), :s, :d, :p, :i)";
$cad_item = $conn->prepare($query_consulta);
$cad_item->bindParam(':s', $stservico, PDO::PARAM_STR);
$cad_item->bindParam(':d', $data, PDO::PARAM_STR);
$cad_item->bindParam(':p', $servico, PDO::PARAM_INT);
$cad_item->bindParam(':i', $descservico, PDO::PARAM_STR);
$cad_item->execute();
}


echo json_encode($retorna);
