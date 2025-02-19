<?php
session_start();
include_once "conexao.php";

 $arquivo = $_FILES["arquivo"]["tmp_name"]; 
 $tamanho = $_FILES["arquivo"]["size"];
 $tipo    = $_FILES["arquivo"]["type"];
 $nome  = $_FILES["arquivo"]["name"];
 $titulo  = $_POST["titulo"];

 if ( $arquivo != "none" )
 {
 $fp = fopen($arquivo, "rb");
 $conteudo = fread($fp, $tamanho);
 $conteudo = addslashes($conteudo);
 fclose($fp); 

 $qry = "INSERT INTO arquivoscliente (idarquivoscliente, descarquivo, titulo, arquivo, tipo) 
        VALUES (0,'$nome','$titulo','$conteudo','$tipo')";
 $cad_usuario = $conn->prepare($qry);
 $cad_usuario->execute();
 if ($cad_usuario->rowCount()) 
    print "O arquivo foi gravado na base de dados.";
 else
 print "Não foi possível gravar o arquivo na base de dados.";
 }
 else
 print "Não foi possível carregar o arquivo para o servidor.";