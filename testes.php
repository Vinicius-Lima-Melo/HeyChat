<?php

$con = mysqli_connect("localhost","root","","heychat");

// $sql = "SELECT remetente, destinatario from amizade where remetente = 1 and status = 2 or destinatario = 1 and status = 2";
// $qry = mysqli_query($con, $sql); 

// echo "AMIGOS:";
// $arr = array();
// $ids = array();

// while($resultado = mysqli_fetch_array($qry)){
// 	if ($resultado[0] != 1 && $resultado[1] == 1) {
// 		// echo "usuario 1 é o destinatario";
// 		array_push($ids, $resultado[0]);
// 	}
// 	else if($resultado[0] == 1 && $resultado[1] != 1){
// 		// echo "usuario 1 é o remetente";
// 		array_push($ids, $resultado[1]);
// 	}
// }
// $qtd = count($ids);
// echo $qtd;

// 	for($i = 0; $i < $qtd; $i++){
// 		$query = mysqli_query($con,"SELECT apelido from usuario where idUsuario = $ids[$i]");
// 		while($resultado = mysqli_fetch_array($query)){
// 		array_push($arr, $resultado[0]);
// 	}
// }
// var_dump($ids);
// var_dump($arr);



// $sqlSolicitacoesEnviadas = "SELECT destinatario from amizade where remetente = 1 and status = 1";
// $qry = mysqli_query($con, $sqlSolicitacoesEnviadas); 
// echo "Enviadas:";
// $arr = array();
// $ids = array();
// while($resultado = mysqli_fetch_array($qry)){
// 	array_push($ids, $resultado[0]);
// }

// $qtd = count($ids);
// echo $qtd;
// for ($i=0; $i < $qtd; $i++) { 
//  	$sql = mysqli_query($con,"SELECT apelido from usuario where idUsuario = $ids[$i]");
//  	while($resultado = mysqli_fetch_array($sql)){
// 		array_push($arr,$resultado);
// 	}
//  } 
// var_dump($ids);
// var_dump($arr);


// $sqlSolicitacoesRecebidas = "SELECT remetente from amizade where remetente != 1 and destinatario = 1 and status = 1";
// $qry = mysqli_query($con, $sqlSolicitacoesRecebidas); 
// echo "Recebidas:";
// while($resultado = mysqli_fetch_array($qry)){
// 	var_dump($resultado);
// }

?>