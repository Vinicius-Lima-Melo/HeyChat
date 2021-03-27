// var filtro = "";

// function listarContatos(filtro){
// 	// STATUS DA AMIZADE
// 	// console.log(filtro);
// 	$.ajax({
//         url: "funcoes/retornarContatos.php",
//         type: "POST",
//         data: {Filtro:filtro},
//         async: false,
//         success : function(e){
//             var json = JSON.parse(e);
//             console.log(json);
//         }
//     });
// }


// $(document).ready(function() {
// 	$(document).on("click", '.botoesInfo', function(){
// 		let valor = $(this).val();
// 		listarContatos(valor);
// 	});

// 	$(document).on("click", '#btnPesquisar', function(){
// 		let valor = $("#filtro").val();
// 		listarContatos(valor);
// 	});


	// $("#filtro").on("keydown", function(e){
	// 	console.log($("#filtro").val());
	// });

});