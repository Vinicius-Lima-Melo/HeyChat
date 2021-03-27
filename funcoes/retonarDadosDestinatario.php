<?php
	session_start();
	extract($_POST);

	if (isset($Destinatario)) {
		$idRemetente = $_SESSION["idUsuarioLogado"];
		$idDestinatario = $Destinatario;
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$retornarDadosDoUsuario = $objUsuario->retornarDadosDoUsuario($Destinatario);

		print_r(json_encode($retornarDadosDoUsuario));
	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>

