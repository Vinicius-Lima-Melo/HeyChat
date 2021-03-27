<?php
	session_start();
	if (isset($_SESSION['email']) && $_GET['idUsuario'] <> "") {
		$email = $_SESSION['email'];
		include_once 'funcoes/Classes/Usuario.php';
		$objUsuario = new usuario;
		$dadosUsuario = $objUsuario->retornarUsuarioLogado($email);
		$idUsuarioLogado = $dadosUsuario[0];
	}
	else{
		header("Location: index.php");
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Hey Chat | Editar perfil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png"  href="img/susto.png">
    <link rel="stylesheet" href="font/css/all.css">
    <link rel="stylesheet" href="css/css/bootstrap.css">
    <link rel="stylesheet" href="css/css/floating-labels.css">
    <link rel="stylesheet" href="css/css/estilo2.css">
    <script src="js/Jquery-3.5.1.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="css/js/bootstrap.js"></script>
    <script src="js/funcoes_editar.js"></script>

	<script>
		 function PreviewImage() {
        	var oFReader = new FileReader();
       		oFReader.readAsDataURL(document.getElementById("upload").files[0]);

        	oFReader.onload = function (oFREvent) {
            	document.getElementById("img").src = oFREvent.target.result;
        	};
    	};
		$(document).ready(function() {
			$("#btnAlterarFoto").click(function() {
	    		$("#upload").click();
	    		// alert("ASD");
	    	});

	    	$('#upload').change(function(){
	    		PreviewImage();
	    		let nome = $(this)[0].files[0]['name'];
		        $("#legendaImg").html("");
		        $("#legendaImg").html(nome);
		        $("#nomeImg").val(nome);
		    });
		});
	</script>

</head>
<body style="background: rgba(55,169,191,0.2);">

	
	<nav class="navbar navbar-expand-lg navbar-light" style="background: rgba(55,169,191,0.7);">
   	    <a class="navbar-brand ml-4 text-light" href="PaginaInicial.php"><img src="imgUsuarios/<?php echo $dadosUsuario[3]?>" width="30" height="30" class="align-top mr-2 degrade" id="foto_perfil"><span>Hey, <?= $dadosUsuario[2] ?>!</span></a>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
    	    <div class="navbar-nav">
        	</div>
    	</div>
    </nav>

	<form id="formulario_cadastro" action="funcoes/alterar.php" method="post" enctype="multipart/form-data" style="width: 70%; margin: 0 auto;">
		

		<div class="container bg-transparent mt-3 mb-5 text-center text-muted" style="max-width: 250px; max-height: 150px">

			<img id="img" src="imgUsuarios/<?= $dadosUsuario[3]?>" style="object-fit: scale-down; max-width: 250px; max-height: 150px; border-radius: 10px;" alt="imagem do perfil">

			<small id="legendaImg"><?= $dadosUsuario[3]?></small>
			<input type="hidden" name="nomeImg" id="nomeImg">
			
			<button id="btnAlterarFoto" type="button" class="btn btn-sm btn-light mt-1" style="width: auto;"><i class="fas fa-camera"></i></button>

		</div>
	
	<input type="file" style="display: none" id="upload" name="imgPerfil">

		  	<div class="form-row">
		    	<div class="form-group col-md-6">
		      		<label for="nomeCompleto">Nome completo</label>
		      		<input type="text" class="form-control" name="nomeCompleto" id="nomeCompleto" placeholder="Nome completo" value="<?= $dadosUsuario[1]?>" required>
		    	</div>
		    	<div class="form-group col-md-6">
		      		<label for="apelido">Nome de usuario</label>
		      		<input type="text" class="form-control" name="apelido" id="apelido" placeholder="Nome de usuario" value="<?= $dadosUsuario[2]?>" required> 
		    	</div>
			    
			    <div class="form-group col-md-6">
		    	<label for="endereco">Endereço</label>
		    	<input type="text" class="form-control" name="endereco" id="endereco" placeholder="Rua Oliveira Agostinho Pinto, 977" value="<?= $dadosUsuario[6]?>" required>
		  		</div>
			  	<div class="form-group col-md-6">
					<label for="bairro">Bairro</label>
					<input type="text" class="form-control" name="bairro" id="bairro" placeholder="Bairro" value="<?= $dadosUsuario[7]?>" required>
				</div>
		    	<div class="form-group col-md-6">
		      		<label for="cidade">Cidade</label>
		      		<input type="text" class="form-control" name="cidade" id="cidade" placeholder="Cidade" value="<?= $dadosUsuario[8]?>" required>
		    	</div>
			    <div class="form-group col-md-4">
			      	<label for="estado">Estado</label>
			      	<select id="estado" name="estado" class="form-control" required>
			        	<option selected value="<?= $dadosUsuario[9]?>"><?= $dadosUsuario[9]?></option>
			        	<option value="AC">Acre</option>
					    <option value="AL">Alagoas</option>
					    <option value="AP">Amapá</option>
					    <option value="AM">Amazonas</option>
					    <option value="BA">Bahia</option>
					    <option value="CE">Ceará</option>
					    <option value="DF">Distrito Federal</option>
					    <option value="ES">Espírito Santo</option>
					    <option value="GO">Goiás</option>
					    <option value="MA">Maranhão</option>
					    <option value="MT">Mato Grosso</option>
					    <option value="MS">Mato Grosso do Sul</option>
					    <option value="MG">Minas Gerais</option>
					    <option value="PA">Pará</option>
					    <option value="PB">Paraíba</option>
					    <option value="PR">Paraná</option>
					    <option value="PE">Pernambuco</option>
					    <option value="PI">Piauí</option>
					    <option value="RJ">Rio de Janeiro</option>
					    <option value="RN">Rio Grande do Norte</option>
					    <option value="RS">Rio Grande do Sul</option>
					    <option value="RO">Rondônia</option>
					    <option value="RR">Roraima</option>
					    <option value="SC">Santa Catarina</option>
					    <option value="SP">São Paulo</option>
					    <option value="SE">Sergipe</option>
					    <option value="TO">Tocantins</option>
			      	</select>
			    </div>
			    <div class="form-group col-md-2">
			      	<label for="cep">CEP</label>
				    <input type="text" name="cep" class="form-control" id="cep" placeholder="CEP" value="<?= $dadosUsuario[10]?>" required>
			    </div>
			    <div class="form-group col-md-6">
			      	<label for="email">Email</label>
			      	<input type="email" class="form-control" name="email" id="email" value="<?= $dadosUsuario[4]?>" placeholder="Email" readonly>
			    </div>
			    <div class="form-group col-md-6">
			      	<label for="senha">Confirmar senha</label>
			      	<input type="password" class="form-control" name="senha" id="senha" placeholder="Senha" required>
			    </div>
			</div>
			<button type="submit" class="btn btn-primary align-middle w-25" id="btn_atualizar">Salvar dados</button>
		</form>


</body>
</html>