<!-- <?php
	session_start();
	extract($_POST);

	if (isset($Filtro)) {
		$idUsuarioLogado = $_SESSION["idUsuarioLogado"];
		include 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$retorno = array();
		// validar filtro amigos
		if ($Filtro == "amigos") {
			$retornarAmigos = $objUsuario->retornarAmigos($idUsuarioLogado);
			$msg = "Amigos";
			array_push($retorno, $msg);
			array_push($retorno, $retornarAmigos);
			print_r(json_encode($retorno));
			die();
		}
		// validar filtro solicitacoes
		else if($Filtro == "solicitacoes"){
			$retornarSolicitacoes = $objUsuario->retornarSolicitacoes($idUsuarioLogado);
			$msg = "Recusar/Aceitar";
			array_push($retorno, $msg);
			array_push($retorno, $retornarSolicitacoes);
			print_r(json_encode($retorno));
			die();
		}
		// validar filtro pedidosEnviados
		else if ($Filtro == "pedidosEnviados") {
			$retornarPedidos = $objUsuario->retornarPedidos($idUsuarioLogado);
			$msg = "Solicitação enviada";
			array_push($retorno, $msg);
			array_push($retorno, $retornarPedidos);
			print_r(json_encode($retorno));
			die();
		}
		// validar filtro de nomes
		else{
			$retornarPesquisa = $objUsuario->retornarPesquisa($idUsuarioLogado,$Filtro);
			$msg = "filtro";
			array_push($retorno, $msg);
			array_push($retorno, $retornarPesquisa);
			print_r(json_encode($retorno));
			die();
		}


		

	}
	else{
		echo("Requisição inválida.");
		die();
	}
?>
 -->
