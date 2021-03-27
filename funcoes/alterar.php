<?php

include 'Classes/Usuario.php';

extract($_POST);
extract($_FILES);

if (isset($_POST) ) {
	$objUsuario = new Usuario();
	$validarSenha = $objUsuario->validarSenha($senha);
	if ($validarSenha) {
		if ($nomeImg =="") {
			$nomeImg = "padrao.png";
		}
		$objUsuario->setNome($nomeCompleto);
		$objUsuario->setApelido($apelido);
		$objUsuario->setFotoPerfil($nomeImg);
		$objUsuario->setEmail($email);
		$objUsuario->setSenha($senha);
		$objUsuario->setEndereco($endereco);
		$objUsuario->setCidade($cidade);
		$objUsuario->setBairro($bairro);
		$objUsuario->setEstado($estado);
		$objUsuario->setCep($cep);
		$objUsuario->atualizarUsuario($email);

		if (isset($_FILES['imgPerfil']) && $_FILES['imgPerfil'] != "") {
			$imgPerfil = $_FILES['imgPerfil']['name'];

	        $_UP['pasta'] = '../imgUsuarios/';
	        $_UP['tamanho'] = 1024*1024*1;
	        $_UP['extensoes'] = array('png','jpg','gif','jpeg');
	        $_UP['renomeia'] = false;

	        $extensao = pathinfo($imgPerfil, PATHINFO_EXTENSION);
	        $size = strlen($imgPerfil);

	        if ($_UP['renomeia'] == true) {
	            $nome_final = time().'.'.$extensao;
	        }
	        else {
	            $nome_final = $imgPerfil;
	        } 

	        move_uploaded_file($_FILES['imgPerfil']['tmp_name'],$_UP['pasta'].$nome_final); 
		}

		?>
		<script>alert("ASDASD")</script>
		<?php
		header("Location:http://localhost/treinos/Chat_V3/PaginaInicial.php");
	}
	else{
		header("Location:http://localhost/treinos/Chat_V3/PaginaInicial.php?error=1");
	}
}
else{
	header("Location:http://localhost/treinos/Chat_V3/");
	}
?>