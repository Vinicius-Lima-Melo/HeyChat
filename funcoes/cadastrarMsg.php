<?php
	session_start();
	extract($_POST);
	// var_dump($_POST);

	if (isset($_SESSION['email'], $Mensagem, $Destinatario)) {
		$idRemetente = $_SESSION["idUsuarioLogado"];
		$idDestinatario = $Destinatario;
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$cadastrarConversa = $objUsuario->cadastrarConversa($idRemetente,$idDestinatario);
		$conversa = $objUsuario->retornarIdConversa($idRemetente,$idDestinatario);
		$idConversa = $conversa[0];

		if ($cadastrarConversa) {
			$cadastrarMensagem = "Conversa cadastrada com sucesso!;";
			print_r(json_encode($cadastrarMensagem));
			die();
		}
		else{
			if ($Mensagem == "") {
				$cadastrarMensagem = "error";
				print_r(json_encode($cadastrarMensagem));
				die();
			}
			else{
				$cadastrarMensagem = $objUsuario->cadastrarMensagem($idRemetente,$idDestinatario,$Mensagem,$idConversa);
				$cadastrarMensagem = "Mensagem cadastrada com sucesso!";
				print_r(json_encode($cadastrarMensagem));
				die();
			}
		}

		// var_dump($cadastrarMensagem);

		// print_r(json_encode($cadastrarMensagem));

		// $retorno = array();

		// $retorno["Destinatario"] = $idDestinatario;
		// $retorno["Remetente"] = $idRemetente;
		// $retorno["Mensagem"] = $Mensagem;


		// // array_push($retorno['destinatario'], $idDestinatario);
		// // array_push($retorno, $idRemetente);
		// // array_push($retorno, $Mensagem);

		// print_r(json_encode($retorno));
		// // print_r(json_encode($idRemetente));
		// die();
	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>

		