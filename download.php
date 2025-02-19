<?php
session_start();
include_once "conexao.php";

$qry = "SELECT idarquivoscliente, codigocliente, descarquivo FROM arquivoscliente";
$res = $conn->prepare($qry);
$res->execute();

while($fila = $res->fetch(PDO::FETCH_ASSOC))

{
print "$fila[descarquivo]
<br>
<a href='baixar_arquivo.php?id=$fila[idarquivoscliente]'>Fazer Download</a>
<br>
<br>";
}