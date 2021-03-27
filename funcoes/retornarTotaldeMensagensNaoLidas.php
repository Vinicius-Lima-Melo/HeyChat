<?php
	session_start();
	extract($_POST);

	if (isset($_SESSION['email'])) {
		$idUsuarioLogado = $_SESSION["idUsuarioLogado"];
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$retorno = array();

		$qtd = $objUsuario->retornarTotaldeMensagensNaoLidas($idUsuarioLogado);
		$usuarioLogado = $objUsuario->retornarDadosDoUsuario($idUsuarioLogado);

		array_push($retorno, $qtd);
		array_push($retorno, $usuarioLogado);

		print_r(json_encode($retorno));
		// var_dump($retorno);
	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>

