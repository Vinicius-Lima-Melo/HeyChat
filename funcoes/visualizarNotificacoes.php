<?php
	session_start();
	extract($_POST);

	if (isset($_SESSION['email'])) {
		$idUsuarioLogado = $_SESSION["idUsuarioLogado"];
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();

		$notificacoes = $objUsuario->visualizarNotificacoes($idUsuarioLogado);
		json_encode($notificacoes);
	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>

