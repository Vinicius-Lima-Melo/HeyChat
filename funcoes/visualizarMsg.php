<?php
	session_start();
	extract($_POST);

	if (isset($_SESSION['email'], $Remetente)) {
		$idDestinatario = $_SESSION["idUsuarioLogado"];
		$idRemetente = $Remetente;
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$retorno = $objUsuario->visualizarMensagens($idDestinatario, $idRemetente);
		print_r(json_encode($retorno));
		// var_dump($retorno);
	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>

