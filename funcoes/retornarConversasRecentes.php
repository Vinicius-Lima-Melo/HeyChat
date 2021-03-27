<?php
	session_start();
	extract($_POST);

	if (isset($_SESSION['email'])) {
		$idUsuarioLogado = $_SESSION["idUsuarioLogado"];
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$retorno = array();

		$qtdConversasRecentes = count($objUsuario->retornarQtdConversas($idUsuarioLogado));
		array_push($retorno, $qtdConversasRecentes);

		$conversasRecentes = $objUsuario->listarConversasRecentes($idUsuarioLogado);

		foreach ($conversasRecentes as $linha) {
			if ($linha[1] == $idUsuarioLogado) {
				$vldAmz = $objUsuario->validarAmizade($idUsuarioLogado,$linha[2]);
			}
			else if ($linha[2] == $idUsuarioLogado) {
				$vldAmz = $objUsuario->validarAmizade($idUsuarioLogado,$linha[1]);
			}
				if ($vldAmz) {
					if ($linha[1] == $idUsuarioLogado) {
						$user = $objUsuario->retornarDadosDoUsuario($linha[2]);
					}
					else if ($linha[2] == $idUsuarioLogado) {
						$user = $objUsuario->retornarDadosDoUsuario($linha[1]);
					}
					array_push($retorno, $user);
				}
		}
		print_r(json_encode($retorno));
		// var_dump(json_encode($retorno));
		// print_r($retorno);
	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>

