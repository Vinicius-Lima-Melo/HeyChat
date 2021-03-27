<?php
	session_start();
	extract($_POST);
	var_dump($_POST);

	if (isset($email,$senha)) {
		include_once 'Classes/Usuario.php';
		$objUsuario = new Usuario();
		$retorno = $objUsuario->logarUsuario($email,$senha);

		if ($retorno) {
			$_SESSION['email'] = $email;
			var_dump($retorno);
			header("Location: http://localhost/treinos/Chat_V3/paginaInicial.php");
		}
		else{
			unset($_SESSION);
			session_destroy();
			header("Location: http://localhost/treinos/Chat_V3/index.php?error=1&email=$email");
		}
	}
	else{
		unset($_SESSION);
		session_destroy();
		header("Location: http://localhost/treinos/Chat_V3/index.php");
		
	}

	
	// header("Location: http://localhost/treinos/Chat_V2/paginaInicial.php?email=$email");
?>

<a href="deslogar.php?email=<?php echo $email?>&senha=<?php echo $senha?>">Deslogar</a>