<?php
	session_start();
	if (isset($_SESSION['email'])) {
		$email = $_SESSION['email'];
		include_once 'funcoes/Classes/Usuario.php';
		$objUsuario = new usuario;
		$dadosUsuario = $objUsuario->retornarUsuarioLogado($email);
		$idUsuarioLogado = $dadosUsuario[0];
		$_SESSION["idUsuarioLogado"] = $idUsuarioLogado;
		$con = mysqli_connect("localhost","root","","heychat");
	}
	else{
		header("Location: index.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Hey Chat | <?= $dadosUsuario[2]?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png"  href="img/susto.png">
    <link rel="stylesheet" href="font/css/all.css">
    <link rel="stylesheet" href="css/css/bootstrap.css">
    <link rel="stylesheet" href="css/css/estilo3.css">
    <script src="js/Jquery-3.5.1.js"></script>
    <script src="css/js/bootstrap.js"></script>
    <script src="js/funcoes_pgContato.js"></script>

</head>
<body style="background: #F0F0F0; font-family: sans-serif;">


	<nav class="navbar navbar-expand-lg navbar-light" style="background: rgba(55,169,191,0.7);">
   	    <a class="navbar-brand ml-4 text-light" href="PaginaInicial.php"><img src="imgUsuarios/<?php echo $dadosUsuario[3]?>" width="30" height="30" class="align-top mr-2 degrade" id="foto_perfil"><span>Hey, <?= $dadosUsuario[2] ?>!</span></a>

   	    

        <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
    	    <div class="navbar-nav">
	            <!-- <a class="nav-item nav-link" href="#">Downloads</a> -->
	        	
        	</div>
    	</div>
    	
    	<div class="btn-group dropdown mr-3">
		    <button type="button" class="btn btn-sm btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		        <i class="fas fa-user mr-2"></i>Minha conta
		    </button>
		    <div class="dropdown-menu">
		        <a href="editarPerfil.php?idUsuario=<?php echo $dadosUsuario[0]?>" class="dropdown-item" type="button"><i class="fas fa-user-edit mr-1"></i>Editar perfil</a>
		        <a href="editarPerfil.php?idUsuario=<?php echo $dadosUsuario[0]?>" class="dropdown-item" type="button"><i class="fas fa-cog mr-1"></i>Configurar chat</a>
		        <a href="suporte.php" class="dropdown-item" type="button" target="_blank"><i class="fas fa-question mr-2"></i>Ajuda e suporte</a>
				<div class="dropdown-divider"></div>
		        <a href="funcoes/deslogar.php" class="dropdown-item" type="button"><i class="fas fa-door-open mr-1"></i>Sair da conta</a>
		    </div>
		</div>
		<button class="btn btn-sm btn-transparent mr-3"><i class="fas fa-bell"></i></button>
		<button class="btn btn-sm btn-transparent mr-5"><i class="fas fa-ellipsis-v"></i></button>
    </nav>
    



    <div class="container">
		<div id="filtrar">
          	<form action="novosContatos.php" method="get">
            	<div class="input-group mb-3">
              		<input type="text" name="search" id="search" placeholder="Pesquise pelo nome do usuario" class="form-control">
              		<div class="input-group-append">
                		<button type="submit" class="btn btn-primary" id="btnPesquisar"><i class="fas fa-search"></i></button>
              		</div>
            	</div> 
          	</form>
          	<div id="informacoes">
	        	<?php

				$con = mysqli_connect("localhost","root","","heychat");
				$amigos = mysqli_query($con,"SELECT COUNT(idAmizade) from amizade where remetente = $idUsuarioLogado and status = 2 or destinatario = $idUsuarioLogado and status = 2");
				$solicitacoes = mysqli_query($con,"SELECT COUNT(idAmizade) from amizade where destinatario = $idUsuarioLogado and status = 1");
				$solicitacoesEnviadas = mysqli_query($con,"SELECT COUNT(idAmizade) from amizade where remetente = $idUsuarioLogado and status = 1");

				$Amigos = mysqli_fetch_array($amigos);
				$Solicitacoes = mysqli_fetch_array($solicitacoes);
				$SolicitacoesEnviadas = mysqli_fetch_array($solicitacoesEnviadas);
				?>
				<!-- <button class="btn btn-outline-dark ml-2 botoesInfo" value="amigos">Amigos
				 	<?php echo "(".$Amigos[0].")";?>
			 	</button>
				<button class="btn btn-outline-dark ml-2 botoesInfo" value="solicitacoes">Solicitações <?php echo "(".$Solicitacoes[0].")";?></button>
				<button class="btn btn-outline-dark ml-2 botoesInfo" value="pedidosEnviados">Pedidos enviados <?php echo "(".$SolicitacoesEnviadas[0].")";?></button> -->

				<a href="novosContatos.php?search=amigos" class="btn btn-transparent ml-1 botoesInfo text-dark" value="amigos"><i class="fas fa-users mr-1"></i>Amigos
				 	<?php echo "(".$Amigos[0].")";?>
			 	</a>
				<a href="novosContatos.php?search=solicitacoes" class="btn btn-transparent ml-1 botoesInfo text-dark" value="solicitacoes"><i class="fas fa-user-check mr-1"></i>Solicitações <?php echo "(".$Solicitacoes[0].")";?></a>
				<a href="novosContatos.php?search=enviados" class="btn btn-transparent ml-1 botoesInfo text-dark" value="pedidosEnviados"><i class="fas fa-arrow-circle-right mr-1"></i>Pedidos enviados <?php echo "(".$SolicitacoesEnviadas[0].")";?></a>

        	</div>
        </div>
        

      	<?php
      

        $usuarios = $objUsuario->listarUsuarios($idUsuarioLogado);

        if (isset($_GET['search'])) {
        	if ($_GET['search'] == "") {
        		$usuarios = $objUsuario->listarUsuarios($idUsuarioLogado);
        	}
        	else if ($_GET['search'] == "amigos") {
				$sql = "SELECT remetente, destinatario from amizade where remetente = $idUsuarioLogado and status = 2 or destinatario = $idUsuarioLogado and status = 2";
				var_dump($sql);
				$qry = mysqli_query($con, $sql); 
				$usuarios = array();
				$ids = array();

				while($resultado = mysqli_fetch_array($qry)){
					if ($resultado[0] != $idUsuarioLogado && $resultado[1] == $idUsuarioLogado) {
						// echo "usuario 1 é o destinatario";
						array_push($ids, $resultado[0]);
					}
					else if($resultado[0] == $idUsuarioLogado && $resultado[1] != $idUsuarioLogado){
						// echo "usuario 1 é o remetente";
						array_push($ids, $resultado[1]);
					}
				}
				$qtd = count($ids);
					for($i = 0; $i < $qtd; $i++){
						$query = mysqli_query($con,"SELECT idUsuario, apelido, fotoPerfil, cidade, estado from usuario where idUsuario = $ids[$i]");
						while($resultado = mysqli_fetch_array($query)){
						array_push($usuarios, $resultado);
					}
				}
        	}
        	else if ($_GET['search'] == "solicitacoes") {

        		$sqlSolicitacoesRecebidas = "SELECT remetente from amizade where destinatario = $idUsuarioLogado and status = 1";
				$qry = mysqli_query($con, $sqlSolicitacoesRecebidas); 
				$usuarios = array();
				$ids = array();
				while($resultado = mysqli_fetch_array($qry)){
					array_push($ids, $resultado);
				}
				$qtd = count($ids);
				for ($i=0; $i < $qtd; $i++) { 
					$id = intval($ids[0][$i]);
				 	$sql = mysqli_query($con,"SELECT idUsuario, apelido, fotoPerfil, cidade, estado from usuario where idUsuario = $id");
				 	while($resultado = mysqli_fetch_array($sql)){
						array_push($usuarios,$resultado);
					}
				 } 
        		
        	}
        	else if ($_GET['search'] == "enviados") {
				$sqlSolicitacoesEnviadas = "SELECT destinatario from amizade where remetente = $idUsuarioLogado and status = 1";
				$qry = mysqli_query($con, $sqlSolicitacoesEnviadas); 
				$usuarios = array();
				$ids = array();
				while($resultado = mysqli_fetch_array($qry)){
					array_push($ids, $resultado);
				}
				$qtd = count($ids);
				for ($i=0; $i < $qtd; $i++) { 
					$id = intval($ids[0][$i]);
				 	$sql = mysqli_query($con,"SELECT idUsuario, apelido, fotoPerfil, cidade, estado from usuario where idUsuario = $id");
				 	while($resultado = mysqli_fetch_array($sql)){
						array_push($usuarios,$resultado);
					}
				 } 
        	}
        	else{
          		$usuarios = $objUsuario->filtrarUsuarios($_GET['search'],$idUsuarioLogado);
          	}
        }
        if (empty($usuarios) && isset($_GET['search'])) {
        	if ($_GET['search'] == "enviados") {
        	?>
				<div class="alert alert-primary  text-center" style="position: relative;">
            		Você nao tem nenhuma solicitação aguardando uma resposta.
          		</div>
        	<?php
        	}
        	 else if ($_GET['search'] == "solicitacoes") {
        	?>
        		<div class="alert alert-primary text-center" style="position: relative;">
            		Você não tem nenhuma solicitação para responder.
          		</div>
        	<?php
        	}
        	 else if ($_GET['search'] == "amigos") {
        	?>
				<div class="alert alert-primary text-center" style="position: relative;">
            		Você ainda não fez nenhuma amizade, <a href="#">clique aqui</a> para enviar solicitações para outros usuarios do heychat.
          		</div>
        	<?php
        	}
        	else{
          	?>
          		<div class="alert alert-danger text-center" style="position: relative;">
            		Não existe nenhum dado correspondente.
          		</div>
          	<?php
          }
        }
        else{
      	?>
		<?php

		foreach ($usuarios as $linha) {
			$validaAmizade = $objUsuario->verificarAmizade($idUsuarioLogado, $linha[0]);
            ?>
            <div class="card">
            	<input type="hidden" id="<?=$linha[0]?>">
			  	<img class="card-img-top" src="imgUsuarios/<?= $linha[2]?>">
			  	<div class="card-body text-center">
			  		<div class="nome"><?= $linha[1]?></div>
			  		<div class="local">
			  			<i class="fas fa-map-marker-alt mr-1"></i><?= $linha[3]." - ". $linha[4]?>
			  		</div>
			<?php

				if ($validaAmizade == null || $validaAmizade[0] == 0) {
				?>
					<a href="funcoes/enviarSolicitacao.php?id=<?=$linha[0]?>" class="mt-1 btn btn-primary btn-sm w-100">Enviar solicitação</a>
				<?php
				}
				else if ($validaAmizade[0] == 2) {
				?>
					<a href="#" class="mt-1 btn btn-info btn-sm w-100 disabled">Amigos</a>
				<?php
				}
				else if($validaAmizade[0] == 1){
					if ($validaAmizade[1] == $idUsuarioLogado) {
						?>
						<a href="#" class="mt-1 btn btn-secondary disabled btn-sm w-100">Solicitação enviada</a>
					<?php
					}
					else{
					?>
						<a href="funcoes/respostaSolicitacao.php?id=<?=$linha[0]?>&op=0" style="width: 45%; margin-left: 2.5%" class="mt-1 btn btn-danger float-left btn-sm">Recusar</a>
						<a href="funcoes/respostaSolicitacao.php?id=<?=$linha[0]?>&op=1" style="width: 45%; margin-left: 2.5%" class="mt-1 btn btn-primary btn-sm">Aceitar</a>

					<?php
					}
				}
			?>
			    	


			  	</div>
			</div>
			<?php
            }
        }
		?>
	</div>
</body>
</html>