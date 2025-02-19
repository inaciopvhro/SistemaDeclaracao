<?php

$host = "147.79.86.208";
$user = "inaciolocal";
$pass = "Inacio@2628";
$dbname = "SistemaDeclaracao";
$port = 3306;

try{
    //Conexao com a porta
    $conn = new PDO("mysql:host=$host; port=$port; dbname=" . $dbname, $user, $pass);
    
    //Conexao sem a porta
    //$conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);

}catch(PDOException $erro){
    echo "Erro: Conexão com banco de dados não realizado com sucesso: " . $erro->getMessage();
}