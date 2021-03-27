<?php
	session_start();
	extract($_POST);

	if (isset($_SESSION['email'], $Destinatario)) {
		$idRemetente = $_SESSION["idUsuarioLogado"];
		$idDestinatario = $Destinatario;
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$listarMensagens = $objUsuario->listarMensagens($idRemetente, $Destinatario);
		// var_dump($listarMensagens);

		print_r(json_encode($listarMensagens));
	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>

