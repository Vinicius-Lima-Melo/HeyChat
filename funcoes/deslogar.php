<?php
	
	session_start();
	extract($_SESSION);
	
	include_once 'Classes/Usuario.php';

	$objUsuario = new Usuario();

	$objUsuario->deslogarUsuario($email);
	
	unset($_POST['email']);
	session_destroy();
	header("Location: http://localhost/treinos/Chat_V3/index.php");
?>