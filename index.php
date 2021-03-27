<?php
	session_start();
	if (isset($_SESSION['email'])) {
		header('Location: PaginaInicial.php');
		exit;
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Hey Chat | Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png"  href="img/susto.png">
    <link rel="stylesheet" href="font/css/all.css">
    <link rel="stylesheet" href="css/css/bootstrap.css">
    <link rel="stylesheet" href="css/css/floating-labels.css">
    <link rel="stylesheet" href="css/css/estilo_index.css">
    <script src="js/Jquery-3.5.1.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="css/js/bootstrap.js"></script>
    <script src="js/funcoes.js"></script>
</head>
<body  style="background-image: linear-gradient(rgba(55,169,191,0.8), rgba(255,185,225,0.9)); font-family: sans-serif;">

	<nav class="navbar navbar-expand-lg navbar-light" style="background: rgba(55,169,191,0.7);
	box-shadow: 0px 0px 25px 0px rgba(0,0,0,0.5);">
   	    <a class="navbar-brand ml-4 text-light" href="#"><img src="img/susto.png" width="30" height="30" class="d-inline-block align-top mr-1"><span>Hey Chat</span></a>

   	    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          	<span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
    	    <div class="navbar-nav">
				<a href="#" class="nav-link active text-muted">HeyChat Web</a>
				<a href="#" class="nav-link active text-muted">Recursos</a>
				<a href="#" class="nav-link active text-muted">Segurança</a>
				<a href="#" class="nav-link active text-muted">FAQ</a>
				<div class="btn-group dropdown mr-3">
				    <button type="button" class="btn btn-sm btn-transparent dropdown-toggle text-muted" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				        Download v3.0
				    </button>
				    <div class="dropdown-menu">
				        <a href="#" class="dropdown-item" type="button">Download v2.0</a>
				        <a href="#" class="dropdown-item" type="button">Download v1.2</a>
				        <a href="#" class="dropdown-item" type="button">Download v1.1</a>
				        <a href="#" class="dropdown-item" type="button">Download v1.0</a>
				    </div>
        		</div>
    		</div>
    	</div>
    	<button id="btn_criarConta" class="btn btn-outline-light float-right mr-3"><i class="fas fa-user-plus mr-2"></i>Criar conta</button>
    	<button id="btn_abrirLogin" class="btn btn-outline-light float-right mr-3"><i class="fas fa-users mr-2"></i>Fazer login</button>
    </nav>


	<div id="form_cadastro">
		<h4 class="mb-3 text-center">Preencha todos os dados abaixo corretamente.</h4>
		<form id="formulario_cadastro" action="funcoes/cadastrar.php" method="post" enctype="multipart/form-data">
		  	<div class="form-row">
		    	<div class="form-group col-md-12">
		      		<label for="nomeCompleto">Nome completo</label>
		      		<input type="text" class="form-control" name="nomeCompleto" id="nomeCompleto" placeholder="Nome completo">
		    	</div>
		    	<div class="form-group col-md-6">
		      		<label for="apelido">Nome de usuario</label>
		      		<input type="text" class="form-control" name="apelido" id="apelido" placeholder="Nome de usuario">
		    	</div>
		    	<div class="form-group col-md-6">
		      		<label for="imgPerfil">Imagem de perfil</label>
		      		<input type="text" class="form-control text-muted" id="imgPerfil" name="nomeImg" placeholder="Clique para selecionar uma imagem" readonly >
		      		<input type="file" style="display: none" name="imgPerfil" id="input_escondido">
			    </div>
			    <div class="form-group col-md-6">
			      	<label for="email">Email</label>
			      	<input type="email" class="form-control" name="email" id="email" placeholder="Email">
			      	<div id="feedback_email">
			      		
			      	</div>
			    </div>
			    <div class="form-group col-md-6">
			      	<label for="senha">Senha</label>
			      	<input type="password" class="form-control" name="senha" id="senha" placeholder="Senha">
			      	<div id="feedback_senha">
			      		
			      	</div>
			    </div>
			    <div class="form-group col-md-6">
		    	<label for="endereco">Endereço</label>
		    	<input type="text" class="form-control" name="endereco" id="endereco" placeholder="Rua Oliveira Agostinho Pinto, 977">
		  		</div>
			  	<div class="form-group col-md-6">
					<label for="bairro">Bairro</label>
					<input type="text" class="form-control" name="bairro" id="bairro" placeholder="Bairro">
				</div>
		    	<div class="form-group col-md-6">
		      		<label for="cidade">Cidade</label>
		      		<input type="text" class="form-control" name="cidade" id="cidade" placeholder="Cidade">
		    	</div>
			    <div class="form-group col-md-4">
			      	<label for="estado">Estado</label>
			      	<select id="estado" name="estado" class="form-control">
			        	<option selected value="">Selecione um estado...</option>
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
				    <input type="text" name="cep" class="form-control" id="cep" placeholder="CEP">
			    </div>
			</div>
			<div class="form-group">
			    <div class="form-check">
			      	<input class="form-check-input" type="checkbox" id="termos">
			      	<label class="form-check-label" for="termos">
			       		Eu li e concordo os termos de uso.
			      	</label>
			    </div>
			</div>
			<button type="submit" class="btn btn-primary" id="btn_cadastrar">Cadastrar</button>
		</form>
	</div>


	<div id="form_login" class="container-fluid">
		<form class="form-signin" method="POST" action="funcoes/logar.php">
			<div class="text-center mb-4">
				<h4 class="mb-4 font-weight-normal">Faça o login na sua conta Hey chat!</h4>
			</div>
			<div class="form-label-group">
				<?php 
					if(isset($_GET['email'])){
						?>
						<input type="email" id="email" name="email" class="form-control border-danger" placeholder="E-mail" required value="<?php echo $_GET['email']?>" >
						<label for="email">E-mail</label>
						<?php
					}
					else{
				?>
				<input type="email" id="email" name="email" class="form-control" placeholder="E-mail" required>
				<label for="email">E-mail</label>
				<?php
					}
				?>
			</div>
			<div class="form-label-group">
				<input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
				<label for="senha">Senha</label>
			</div>
			<?php
			if (isset($_GET["error"])) {
			?>
				<div class="alert alert-danger" role="alert">
				  	E-mail ou senha inválidos.
				</div>
			<?php
			}
		?>
			<input type="submit" class="btn btn-primary w-100 mt-3"  value="Logar">
		</form>

		

	</div>


	<div id="rodape">

            <div class="text-center" style="margin-top: 1.3%; font-size: 15px; font-family: arial; width: 50%; margin-left: 25%; float: left;">
                <p class="text-light">Todos os direitos reservados</p>
            </div>

            <div id="redesSociais" class="justify-content-end" style="float: left; width: 25%; margin-top: 1%; padding-right: 5%">
                
            </div>
        </div> 
</body>
</html>