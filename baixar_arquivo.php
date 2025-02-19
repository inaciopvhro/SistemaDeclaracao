<?php
session_start();
include_once "conexao.php";

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="imagens/favicon.ico">
    <link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title>Inacio Informatica - Download</title>
</head>
<body>

<?php

if(isset($_GET['idp']))
{
$id = $_GET['idp'];
$idc = $_GET['td'];
$qry = "SELECT arquivo, tipo FROM arquivoscliente 
        WHERE idpedido=:nr and tipoarquivo=:t and codigocliente=:c";
$res = $conn->prepare($qry);        
$res->bindParam(':nr', $id, PDO::PARAM_INT); 
$res->bindParam(':t', $idc, PDO::PARAM_STR); 
$res->bindParam(':c', $_SESSION['id'], PDO::PARAM_INT); 
$res->execute();
if(($res) and ($res->rowCount() != 0)){
        $row_arquivos = $res->fetch(PDO::FETCH_ASSOC);
        $tipos = $row_arquivos['tipo'];
        $conteudo = $row_arquivos['arquivo'];
 
        header('Content-type: ' . $tipos);
        print $conteudo;
        } else {
        ?>
        <div class="m-5">
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>Arquivo para download não localizado
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
        </div>
    <?php

        }
}?>
<script src="./assets/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>