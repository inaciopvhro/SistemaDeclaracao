<?php
session_cache_expire(300);
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
    <title>Inacio Informatica - Área Restrita</title>
</head>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-H8Q0PWMHNX');
</script>
<body>
    <?php
    if(isset($_SESSION['id']) and (isset($_SESSION['nome']))){
        echo "<div class='container text-center'>";
            echo "<header class='d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom'>";
                echo "<div class='container text-center'>";
                    echo "Bem vindo " . $_SESSION['nome'] . "<br>";
                    echo "<a><button type='button' class='btn btn-outline-success' data-bs-toggle='modal' data-bs-target='#cadDeclaracaoModal'>Fazer Pedido Declaração</button></a>";
                    echo "     <a href='sair.php'><button type='button' class='btn btn-outline-danger'>Sair</button></a></a>";
                echo "</div>";
            echo "</header>";
        echo "</div>";
    ?>
    <main>
        <h2>Pedidos do Cliente</h2>
     
        <div class='table-responsive small'>
            <table class='table table-striped table-sm'>
            <thead class="table-dark">
            <tr>
            <th scope='col'>#</th>
            <th scope='col'>Data Abertura</th>
            <th scope='col'>Data Fechamento</th>
            <th scope='col'>Situação</th>
            <th scope='col'></th>
            </tr>
            </thead>
            <tbody id='myTable'>
            <?php
                $query_usuario = "SELECT * , DATE_FORMAT(dataabertura, '%d/%m/%Y %Hh%i') AS DATAABR, 
                        DATE_FORMAT(datafechamento, '%d/%m/%Y %Hh%i') AS DATAF 
                        FROM vwpedidos
                        WHERE cpfcliente=:i";
                $result_usuario = $conn->prepare($query_usuario);
                $result_usuario->bindParam(':i', $_SESSION['cpf'], PDO::PARAM_STR);
                $result_usuario->execute();
                if(($result_usuario) and ($result_usuario->rowCount() != 0)){
                    while($row = $result_usuario->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td class='table-primary'>" . $row["idpedidos"] . "</td>";
                        echo "<td class='table-primary'>" . $row["DATAABR"] . "</td>";
                        echo "<td class='table-primary'>" . $row["DATAF"] . "</td>";
                        echo "<td class='table-primary'>" . $row["Situacao"] . "</td>";
                        echo "<td class='table-primary'></td>";
                        $query_itens = "SELECT * FROM ItensPedido WHERE idPedidos=:i";
                        $result_itens = $conn->prepare($query_itens);
                        $result_itens->bindParam(':i', $row["idpedidos"], PDO::PARAM_INT);
                        $result_itens->execute();
                        if(($result_itens) and ($result_itens->rowCount() != 0)){
                            while($rows = $result_itens->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td class='table-secondary'> * </td>";
                                echo "<td class='table-secondary'>" . $rows["descitem"] . "</td>";
                                if ($rows["statusIten"] === '1') {
                                    $status = 'Aberto';
                                }else if ($rows["statusIten"] === '2') {
                                    $status = 'Em Processamento';
                                }else if ($rows["statusIten"] === '3') {
                                    $status = 'Aguardando Doc';
                                }else if ($rows["statusIten"] === '4') {
                                    $status = 'Pronta';
                                }else if ($rows["statusIten"] === '5') {
                                    $status = 'Pronta/Enviada';
                                }
                                echo "<td class='table-secondary'>" . $status . "</td>";
                                if (empty($rows['DownloadRecibo'])) {
                                    echo "<td class='table-secondary'><a>Recibo não disponivel</a></td>";    
                                } else {
                                    echo "<td class='table-secondary'><a href='baixar_arquivo.php?id=$rows[idItensPedido]&idt=1'>Recibo</a></td>";
                                }
                                if (empty($rows['DownloadDeclaracao'])) {
                                    echo "<td class='table-secondary'><a>Declaração não disponivel</a></td>";    
                                } else {
                                    echo "<td class='table-secondary'><a href='baixar_arquivo.php?id=$rows[idItensPedido]&idt=2'>Declaração</a></td>";
                                }
                                
                                
                        }}

                        echo "</tr>";
                    }
                } else {echo "Nenhum Pedido encontrado"; }
?>              
            </tbody>
            </table>
        </div>
    </main>
    <?php } else { ?>
        <header class='d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom'>
            <div class='container text-center'>
                <div id='dados-usuario'>
                    <a href="/"><button type="button" class="btn btn-outline-success">Voltar Pagina Inicial</button></a>
                </div>
            </div>
        </header>

    <?php  } ?>
       
    <div class="m-5">
        <span id="msgAlert"></span>
    </div>

    
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Área Restrita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="login-usuario-form">
                        <span id="msgAlertErroLogin"></span>
                        <div class="mb-3">
                            <label for="cpf" class="col-form-label">CPF:</label>
                            <input type="text" name="cpf" class="form-control" id="cpf" placeholder="Digite o CPF">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="col-form-label">Senha:</label>
                            <input type="password" name="senha" class="form-control" id="senha" autocomplete="on" placeholder="Digite a senha">
                        </div>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-outline-primary bt-sm" id="login-usuario-btn" value="Acessar">
                            <a>    ainda não tem cadastro ? <button type='button' class='btn btn-outline-success' data-bs-toggle='modal' data-bs-target='#cadUsuarioModal'>Cadastrar</button></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>      
 
    <div class="modal fade" id="cadUsuarioModal" tabindex="-1" aria-labelledby="cadUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadUsuarioModalLabel">Cadastrar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="cad-usuario-form">
                        <span id="msgAlertErroCad"></span>
                        <div class="mb-3">
                            <label for="cadnome" class="col-form-label">Nome:</label>
                            <input type="text" name="cadnome" class="form-control" id="cadnome" placeholder="Digite o nome completo">
                        </div>
                        <div class="mb-3">
                            <label for="cadcpf" class="col-form-label">CPF:</label>
                            <input type="text" name="cadcpf" class="form-control" id="cadcpf" placeholder="Digite o seu CPF">
                        </div>
                        <div class="mb-3">
                            <label for="cademail" class="col-form-label">Telefone:</label>
                            <input type="text" name="cademail" class="form-control" id="cademail" placeholder="Digite nº telefone (WhatsApp)">
                        </div>                        
                        <div class="mb-3">
                            <label for="cadsenha" class="col-form-label">Senha:</label>
                            <input type="password" name="cadsenha" class="form-control" id="cadsenha" autocomplete="on" placeholder="Digite a senha">
                        </div>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-outline-success bt-sm" id="cad-usuario-btn" value="Cadastrar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    <div class="modal fade" id="cadDeclaracaoModal" tabindex="-1" aria-labelledby="cadDeclaracaoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadDeclaracaoModalLabel">Cadastrar Pedido Declaração</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="cad-Pedido-form">
                        <span id="msgAlertErroCadPedido"></span>
                        <div class="mb-3">
                            
                            <select class="form-select form-select-lg" id="cadservico" name="cadservico" aria-label="Small select example">
                            <option value="0" selected>selecione o serviço desejado</option>
                                <option value="2">Declaração Completa R$ 150,00</option>
                                <option value="1">Declaração Simplificada R$ 100,00</option>
                            </select>
                        </div>
                        <br>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-outline-success bt-sm" id="cad-pedido-btn" value="Fazer Pedido">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    <script src="./assets/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="./assets/js/custom.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>  
    <script>
        <?php
        if(!isset($_SESSION['id']) and (!isset($_SESSION['nome']))) {
            ?>
           $(document).ready(function() {
                $('#loginModal').modal('show');
           });
       <?php }?>
    </script>   
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        	document.getElementById('cpf').addEventListener('input', function(e) {
		        var value = e.target.value;
        		var cpfPattern = value.replace(/\D/g, '')
		        			  .replace(/(\d{3})(\d)/, '$1.$2')
							  .replace(/(\d{3})(\d)/, '$1.$2')
							  .replace(/(\d{3})(\d)/, '$1-$2')
							  .replace(/(-\d{2})\d+?$/, '$1');
		        e.target.value = cpfPattern;
	        });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        	document.getElementById('cadcpf').addEventListener('input', function(e) {
		        var value = e.target.value;
        		var cpfPattern = value.replace(/\D/g, '')
		        			  .replace(/(\d{3})(\d)/, '$1.$2')
							  .replace(/(\d{3})(\d)/, '$1.$2')
							  .replace(/(\d{3})(\d)/, '$1-$2')
							  .replace(/(-\d{2})\d+?$/, '$1');
		        e.target.value = cpfPattern;
	        });
        });
    </script>     
</body>

</html>