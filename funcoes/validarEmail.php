<?php
	
extract($_POST);

include_once 'Classes/Usuario.php';

$objUsuario = new Usuario();
$validaEmail = $objUsuario->validarEmail($Email);
print_r(json_encode($validaEmail));
?>