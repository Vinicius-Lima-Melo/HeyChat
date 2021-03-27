<?php
	session_start();
	extract($_GET);

	if (isset($_SESSION['email'])) {
		$idUsuarioLogado = $_SESSION["idUsuarioLogado"];
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$cadastrar = $objUsuario->responderSolicitacao($idUsuarioLogado,$id,$op);
	}
	header("Location: http://localhost/treinos/Chat_V3/novosContatos.php");
?>

