<?php
	session_start();
	if (isset($_SESSION['email'])) {
		$email = $_SESSION['email'];
		include_once 'funcoes/Classes/Usuario.php';
		$objUsuario = new usuario;
		$dadosUsuario = $objUsuario->retornarUsuarioLogado($email);
		$idUsuarioLogado = $dadosUsuario[0];
		$_SESSION["idUsuarioLogado"] = $idUsuarioLogado;
		$conversasRecentes = $objUsuario->listarConversasRecentes($idUsuarioLogado);
		$qtdConversasRecentes = count($objUsuario->retornarQtdConversas($idUsuarioLogado));
		$usuariosOnline = $objUsuario->listarUsuariosOnline($idUsuarioLogado);
		$contatos = $objUsuario->listarContatos($idUsuarioLogado);
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
    <link rel="stylesheet" href="css/css/floating-labels.css">
    <link rel="stylesheet" href="css/css/estilo2.css">
    <script src="js/Jquery-3.5.1.js"></script>
    <script src="css/js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js" integrity="sha512-v8ng/uGxkge3d1IJuEo6dJP8JViyvms0cly9pnbfRxT6/31c3dRWxIiwGnMSWwZjHKOuY3EVmijs7k1jz/9bLA==" crossorigin="anonymous"></script>
    <!-- <script src="js/funcoes_pgInicial.js"></script> -->
    <script src="js/functions.js"></script>
</head>
<body>

<!-- <audio style="display: none" id="notificacao" preload="auto">
  <source src="notification.mp3" type="audio/mpeg">
</audio> -->
<input type="hidden" id="idUsuarioLogado" value="<?=$idUsuarioLogado?>">

	<nav class="navbar navbar-expand-lg navbar-light" style="background: rgba(55,169,191,0.7);">
   	    <a class="navbar-brand ml-4 text-light" href="#"><img src="imgUsuarios/<?php echo $dadosUsuario[3]?>" class="align-top mr-2 degrade" id="foto_perfil"><span>Hey, <?= $dadosUsuario[2] ?>!</span></a>

   	    

        <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
    	    <div class="navbar-nav">
	            <!-- <a class="nav-item nav-link" href="#">Downloads</a> -->
	            <a class="nav-item nav-link" href="#">Logado: <span id="usuarioLogado"></span></a>
	            <a class="nav-item nav-link" href="#">Selecionado: <span id="usuarioSelecionado"></span></a>
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

		<ul class="nav navbar-nav navbar-right">
	      	<li class="dropdown">
	       		<a href="#" id="btnNotificacao" class="btn btn-sm btn-transparent mr-3" data-toggle="dropdown">
	       			<i class="fas fa-bell"></i> 
					<span class="badge badge-pill badge-danger" id="qtdNotificacao"></span>
	       		</a>
	       		<div class="dropdown-menu dropdown-menu-right" id="notificacoes">
	       			
	       		</div>
	    	</li>
	    </ul>

		<!-- <button class="btn btn-sm btn-transparent mr-3">
			<i class="fas fa-bell"></i> 
			<span class="badge badge-pill badge-danger" id="qtdNotificacao"></span>
		</button> -->
	
		<button class="btn btn-sm btn-transparent mr-5"><i class="fas fa-ellipsis-v"></i></button>
    </nav>

    <div id="menu_lateral">
    	<nav class="navbar navbar-expand-lg navbar-light" style="background: rgba(55,169,191,0.2);">
    	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          	<span class="navbar-toggler-icon"></span>
        </button>
	        <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
	    	    <div class="navbar-nav">
		            <a class="nav-item nav-link" id="listarRecentes" href="#">Recentes</a>
		            <a class="nav-item nav-link" id="listarOnlines" href="#">Onlines</a>
		            <a class="nav-item nav-link" id="listarContatos" href="#">Contatos</a>
	        	</div>
	    	</div>
	    </nav>

		<div id="pesquisa">
			<div class="input-group mb-3">
			  	<input type="text" class="form-control" id="inputPesquisa" placeholder="Pesquisar mensagens">
			  	<div class="input-group-append">
			  	  <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
			  	</div>
			</div>
		</div>
		
		<div id="menu_recentes">
			
			<!-- <?php
			// var_dump($conversasRecentes);
				for ($i= 0; $i < $qtdConversasRecentes ; $i++) { 
			?>
			<div class="conversa" id="<?php echo $conversasRecentes[$i][0]?>">
				<div class="img_usuario">
					<img src="imgUsuarios/<?php echo $conversasRecentes[$i][2]?>">
				</div>
				<?php
					$ultimaMsg = $objUsuario->listarUltimaMensagem($idUsuarioLogado,$conversasRecentes[$i][0]);
				?>
				<div class="nome_usuario">
					<p><?= $conversasRecentes[$i][1]?></p>
				</div>
				<div class="horario_msg">
						<p><?= $ultimaMsg[1]?></p>
				</div>
				<div class="mensagem_conversa">
					<div class="texto" >
						
						<p><?= $ultimaMsg[0]?></p>
					</div>
					<?php
					$qtdMensagens = $objUsuario->retornarQtdMensagens($idUsuarioLogado,$conversasRecentes[$i][0]);
					if ($qtdMensagens[0] != 0) {
					?>
					<div class="qtd_msg">
						<p><?= $qtdMensagens[0]?></p>
					</div>
						<?php
					}
					?>
					
				</div>
			</div>
			<?php
				}
			?> -->

			
			
		</div>

		<div id="menu_onlines">
			
			<?php

			foreach($usuariosOnline as $Lista_Online) {
				$validaAmizade = $objUsuario->validarAmizade($idUsuarioLogado, $Lista_Online[0]);
				if ($validaAmizade) {
			?>
			<div class="listagem_onlines_contatos">
				<div class="img_onlines_contatos">
					<img src="imgUsuarios/<?php echo $Lista_Online[3]?>">
				</div>
				<div class="nome_usuario_onlines_contatos">
					<p class="font-weight-bold"><?= $Lista_Online[1]?></p>
				</div>
				<div class="status_opcoes">
					<p>
						Online
						<i class="fas fa-comments ml-1 mr-2 contatos-i " id="<?= $Lista_Online[0]?>"></i>
					</p>
				</div>
			</div>
			<?php
				}
				// var_dump($Lista_Online);
			}
			?>

		</div>
		
		<div id="menu_contatos">
			<a href="novosContatos.php"><i class="fas fa-user-plus mr-2 ml-2"></i>Adicionar contatos</a>

			<?php
			
			foreach($contatos as $Lista_Contato) {
			$validaAmizade = $objUsuario->validarAmizade($idUsuarioLogado, $Lista_Contato[0]);
			// var_dump($validaAmizade);

			if ($validaAmizade) {
				if ($Lista_Contato[11] == 1) 
					$status = "Usuario Online";
				else if($Lista_Contato[11] == 0 && $Lista_Contato[12] != "")
					$status = "Hoje às ".date("H:i", strtotime("$Lista_Contato[12]"));
				else 
					$status = "Nao logou";
			?>

				<div class="listagem_onlines_contatos">
					<div class="img_onlines_contatos">
						<img src="imgUsuarios/<?php echo $Lista_Contato[3]?>">
					</div>
					<div class="nome_usuario_onlines_contatos">
						<p class="font-weight-bold"><?= $Lista_Contato[2]?></p>
					</div>
					<div class="status_opcoes">
						<p>
							<?= $status?>
							<i class=""></i>
							<i class="fas fa-comments ml-1 mr-2 contatos-i " id="<?= $Lista_Contato[0]?>"></i>
							<!-- <i class="fas fa-video  "></i> -->
						</p>
					</div>
				</div>
			<?php

				// var_dump($Lista_Online);
			}
		}

			?>
		</div>

    </div>
	
	<div id="chat">
		<div id="cabecalho">
			<div id="dados_usuario">
				<div id="img_usuario_chat">
				</div>
				<div id="nome_usuario_chat">
					<p id="destinatario"></p>
					<input type="hidden" id="idDestinatario" ">
				</div>
				<div id="status_usuario_chat">
					<!-- <p>online</p> -->
					<!-- <p> -->
						<!-- <?php
							if ($conversasRecentes[0][3] == 1) {
								echo('Online');
							}
							else{
								$data = $conversasRecentes[0][4];
								$vistoPorUltimo = date("H:i", strtotime("$data"));
								echo('Visto por último hoje às '.$vistoPorUltimo);
							}
						?> -->
					<!-- </p> -->
				</div>
			</div>

			<div id="opcoes_conversa">
				<i class="fas fa-camera ml-3"></i>
				<i class="fas fa-video"></i>
				<i class="fas fa-microphone"></i>

				<i class="fas fa-cog ml-4"></i>
			</div>
		</div>	
		
		<div id="body_chat">
			<div class="alert alert-primary text-center" role="alert">
			  Continue ou crie uma nova conversa no seu HeyChat agora mesmo!!
			</div>	
				<!-- <div class="mensagem_direita mt-1">
					<div class="box_direita">
						<div class="texto_direita">
							<p id="msgDireita">teste</p>
						</div>
						<div class="status_msg"><i class="fas fa-check-double"></i></div>
						<div class="horario_envio_mensagem_direita">
						20:20
						</div>
					</div>
				</div> -->
			<!-- <div class="mensagem_esquerda container mt-1">
				<div class="box_esquerda ">
					<div class="texto_esquerdo">
						<p id="msgEsquerda"> asdasdasasdasdasdasdasdasdasd</p>
					</div>
					<div class="horario_envio_mensagem">
						20:20
					</div>	
					
				</div>
			</div> -->
<!-- <div class="mensagem_esquerda container mt-1"><div class="box_esquerda"><div class="texto_esquerdo"><p>'+o[1]+'</p></div><div class="horario_envio_mensagem">'+o[2]+'</div></div></div> -->

<!-- <div class="mensagem_direita mt-1"><div class="box_direita"><div class="texto_direita"><p id="msgDireita">'+o[1]+'</p></div><div class="horario_envio_mensagem_direita">'+o[2]+'</div></div></div> -->

			<!-- <div class="mensagem_direita mt-1">
				<div class="box_direita">
					<div class="texto_direita">
							<p id="msgDireita">asdasdasdasdassssssssssssssssss</p>
					</div>
					<div class="horario_envio_mensagem_direita">
						20:21
					</div>
				</div>
			</div> -->

		</div>
	<!-- <button class="btn bg-transparent btnRolarConteudo" id="btnRolarConteudo"></button> -->
	<button class="btn bg-transparent btnRolarConteudo" id="btnRolarConteudo"></button>
	<!-- <button class="btn bg-transparent btnRolarConteudo" id="btnRolarConteudo">(1) <i class="fas fa-angle-double-down"></i></button> -->

	
		<div id="entrada">
			<div class="input-group" id="input_chat">
				<div class="input-group-prepend">
			  	  <button class="btn btn-primary" type="button"><i class="far fa-smile"></i></button>
				</div>
			  	<input type="text" id="txtMensagem" class="form-control" placeholder="Digite sua mensagem aqui">
			  	<div class="input-group-append">
			  	  <button class="btn btn-primary" id="btnEnviarMsg" type="button">
			  	  	<i id="iconeEnviar" class="far fa-paper-plane"></i>
			  	  </button>
			  	</div>
			</div>
		</div>

	</div>


	

</body>
</html>