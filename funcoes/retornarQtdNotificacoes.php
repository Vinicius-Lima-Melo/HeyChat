<?php
	session_start();
	extract($_POST);

	if (isset($_SESSION['email'])) {
		$idUsuarioLogado = $_SESSION["idUsuarioLogado"];
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$retorno = array();
		
		$qtd = $objUsuario->retornarQtdNotificacoesNaoVistas($idUsuarioLogado);
		$qtdTotal = $objUsuario->retornarQtdNotificacoes($idUsuarioLogado);
		$notificacoes = $objUsuario->retornarNotificacoes($idUsuarioLogado);
		// var_dump($retorno);
		array_push($retorno, $qtd);
		array_push($retorno, $notificacoes);
		array_push($retorno, $qtdTotal);

		print_r(json_encode($retorno));
	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>

