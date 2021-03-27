<?php
	session_start();
	extract($_POST);

	if (isset($_SESSION['email'])) {
		$idUsuarioLogado = $_SESSION["idUsuarioLogado"];
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$retorno = array();
		
		$qtd = count($Ids);
		// print_r(json_encode($Ids[0]));

		$retorno["quantidade"] = $qtd;

		for($i = 0; $i < $qtd; $i++){
			unset($ultima);
			$ultima = array();
			$idDestinatario = $Ids[$i]; 
			$ult = $objUsuario->retornarQtdMensagens($idUsuarioLogado,$idDestinatario);
			array_push($ultima, $idDestinatario);
			array_push($ultima, $ult);
			$retorno[$i] = $ultima;
		}
		

		print_r(json_encode($retorno));
		// var_dump($retorno);
	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>

