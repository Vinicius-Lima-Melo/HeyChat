var socket = io.connect('http://localhost:3000');
// 
// DECLARAÇÃO DE VARIAVEIS GLOBAIS
var idDes = 0;
var usuarioLogado = 0;

// FUNÇOES FRONT-END

function removerNegrito(){
	$("#listarRecentes").css('font-weight', 'normal');
	$("#listarOnlines").css('font-weight', 'normal');
	$("#listarContatos").css('font-weight', 'normal');
}

$(document).ready(function() {
	$("#listarRecentes").css('font-weight', 'bold');
	$("#listarRecentes").click(function() {
		removerNegrito();
		$("#menu_onlines").css('display', 'none');
		$("#menu_contatos").css('display', 'none');
		$("#menu_recentes").fadeIn(400);
		$("#listarRecentes").css('font-weight', 'bold');
		$("#inputPesquisa").prop('placeholder', 'Pesquisar mensagens');
	});
	$("#listarOnlines").click(function() {
		removerNegrito();
		$("#menu_recentes").css('display', 'none');
		$("#menu_contatos").css('display', 'none');
		$("#menu_onlines").fadeIn(400);
		$("#listarOnlines").css('font-weight', 'bold');
		$("#inputPesquisa").prop('placeholder', 'Pesquisar usuarios onlines');
	});
	$("#listarContatos").click(function() {
		removerNegrito();
		$("#menu_recentes").css('display', 'none');
		$("#menu_onlines").css('display', 'none');
		$("#menu_contatos").fadeIn(400);
		$("#listarContatos").css('font-weight', 'bold');
		$("#inputPesquisa").prop('placeholder', 'Pesquisar contatos');
	});
});

// FUNÇOES BACK-END

function atualizarMensagens(){
  	setTimeout(atualizarMensagens, 2000);
  	listarConversasRecentes();
	// listarMensagem(idDes);	

  	// console.log("Atualizando");
}

function atualizarDados(){
  	setTimeout(atualizarDados, 5000);
  
  	retornarQtdNotificacoes();
	retornarTotaldeMensagensNaoLidas();
	retornarQtdMensagensNaoLidas();
	scrollBottom = $("#body_chat").scrollTop() + $("#body_chat").height();
}


function retonarDadosDestinatario(destinatario){
	$.ajax({
        url: "funcoes/retonarDadosDestinatario.php",
        type: "POST",
        data: {Destinatario:destinatario},
        async: false,
            success : function(e){
                var json = JSON.parse(e);
                console.log(json);
                $("#destinatario").html("");
                $("#idDestinatario").html("");
                $("#img_usuario_chat").html('');
                $("#status_usuario_chat").html('');

                $("#destinatario").html(json['apelido']);
                $("#idDestinatario").val(json['idUsuario']);
                $("#img_usuario_chat").append('<img src="imgUsuarios/'+json['fotoPerfil']+'">');
                if (json['status'] == 1) {
                	$("#status_usuario_chat").append('<p>Online</p>');
                }
                else{
                	$("#status_usuario_chat").append('<p>'+json['ultimoAcesso']+'</p>');
                }
        	}
    });
}

function visualizarMensagens(remetente){
	if (scroll < scrollBottom && scroll != 0) {
        
    } else {
   	   $.ajax({
        url: "funcoes/visualizarMsg.php",
        type: "POST",
        data: {Remetente:remetente},
        async: false,
            success : function(e){
                var json = JSON.parse(e);
                // console.log(json);
        	}
    	});
    }
	
}

function enviarMensagem(msg, destinatario){

	socket.emit('enviarMensagem', usuarioLogado, destinatario, msg);

	listarMensagem(destinatario);
	// $.ajax({
 //        url: "funcoes/cadastrarMsg.php",
 //        type: "POST",
 //        data: {Mensagem:msg, Destinatario:destinatario},
 //        async: false,
 //            success : function(e){
 //                var json = JSON.parse(e);
 //                console.log(json);
 //                $("#iconeEnviar").addClass('fa-spin');
 //                $("#iconeEnviar").addClass('text-dark');
 //        	}
 //    });
 //    $("#iconeEnviar").removeClass('fa-spin');
 //    $("#iconeEnviar").removeClass('text-dark');

 //    $("#txtMensagem").val("");



}

function listarMensagem(destinatario){    
	socket.emit('listarMensagens', usuarioLogado, destinatario);
	// alert(destinatario);
	socket.on('get mensagens', function(retorno){
		$("#body_chat").html("");
		for(i = 0; i < retorno.length; i++){
		// console.log(retorno[i]);

			if (retorno[i]['remetente'] != destinatario) {
				if (retorno[i]['status'] == 1) {
					$("#body_chat").append('<div class="mensagem_direita mt-1"><div class="box_direita"><div class="texto_direita"><p id="msgDireita">'+retorno[i]['texto']+'</p></div><div class="status_msg"><i class="far fa-eye"></i></div><div class="horario_envio_mensagem_direita">'+retorno[i]['horarioEnvio']+'</div></div></div>');
				}
				else{
					$("#body_chat").append('<div class="mensagem_direita mt-1"><div class="box_direita"><div class="texto_direita"><p id="msgDireita">'+retorno[i]['texto']+'</p></div><div class="status_msg"></div><div class="horario_envio_mensagem_direita">'+retorno[i]['horarioEnvio']+'</div></div></div>');
				}
			}
			else{
				$("#body_chat").append('<div class="mensagem_esquerda container mt-1"><div class="box_esquerda"><div class="texto_esquerdo"><p>'+retorno[i]['texto']+'</p></div><div class="horario_envio_mensagem">'+retorno[i]['horarioEnvio']+'</div></div></div>');
			}
		}
	});

  	$("#txtMensagem").focus();
    visualizarMensagens(destinatario);
}


function listarConversasRecentes(){
	$.ajax({
        url: "funcoes/retornarConversasRecentes.php",
        type: "POST",
        async: false,
	        success : function(e){
                var json = JSON.parse(e);
				// console.log(json);
				$("#menu_recentes").html("");
				for(i = 1; i <= json[0]; i++){
					$("#menu_recentes").append('<div class="conversa" id="'+json[i]['idUsuario']+'"><div class="img_usuario"><img src="imgUsuarios/'+json[i]['fotoPerfil']+'"></div><div class="nome_usuario"><p>'+json[i]['apelido']+'</p></div><div class="horario_msg" id="hora'+json[i]['idUsuario']+'"></div><div class="mensagem_conversa"><div class="texto" id="texto'+json[i]['idUsuario']+'"></div><div class="qtd_msg" style="display:none;" id="qtd'+json[i]['idUsuario']+'"></div></div></div></div>');
				}      	
			}
    });
	listarUltimaMensagem();
}

function retornarQtdMensagensNaoLidas(){
	var Ids = new Array();
	$('.conversa').each(function(){
		Ids.push($(this).prop('id'));
	});
	$.ajax({
        url: "funcoes/retornarQtdMensagensNaoLidas.php",
        type: "POST",
        data: {Ids:Ids},
        async: false,
            success : function(e){
                var json = JSON.parse(e);
               // console.log(json);
                $(".qtd_msg").html("");
					for(i = 0; i < json["quantidade"]; i++){

						if (json[i][1][0] != 0) {
                			$('#qtd'+json[i][0]).append('<p>'+json[i][1][0])+'</p>';
                			$('#qtd'+json[i][0]).css('display', 'block');
                			$('#texto'+json[i][0]).css('font-weight', 'bold');
                			if (json[i][0] == idDes) {
    	           				$('#btnRolarConteudo'+idDes).html('');
	                			$('#btnRolarConteudo'+idDes).append('('+json[i][1][0]+') <i class="fas fa-angle-double-down"></i>');
                			}
						}
						else{
							$('#qtd'+json[i][0]).css('display', 'none');
                			$('#texto'+json[i][0]).css('font-weight', 'normal');
                			if (json[i][0] == idDes) {
                				$('#btnRolarConteudo'+idDes).html('');
                				$('#btnRolarConteudo'+idDes).append('<i class="fas fa-angle-double-down"></i>');
                			}
						}
					}
        	}
    });
    listarUltimaMensagem();
}

function retornarTotaldeMensagensNaoLidas(){
	$.ajax({
        url: "funcoes/retornarTotaldeMensagensNaoLidas.php",
        type: "POST",
        async: false,
            success : function(e){
                var json = JSON.parse(e);
				// console.log(json);
				if (json[0][0] != 0) {
					$(document).prop('title', "("+json[0][0]+") Hey Chat | "+json[1]['apelido']+"");
				}
				else{
					$(document).prop('title', "Hey Chat | "+json[1]['apelido']+"");
				}
			}
    });
}

function retornarQtdNotificacoes(){
	$.ajax({
        url: "funcoes/retornarQtdNotificacoes.php",
        type: "POST",
        async: false,
            success : function(e){
                // console.log(e);
                var json = JSON.parse(e);

				$("#qtdNotificacao").html("");
				$("#notificacoes").html("");
				// console.log(json);
				if (json[0][0] != 0) {
					$("#qtdNotificacao").append(json[0][0]);
				}
				for(i = 0; i < json[2][0]; i++){
					$("#notificacoes").append('<a class="dropdown-item" id="'+json[1][i][0]+'" href="novosContatos.php">'+json[1][i][1]+'</a>');
				}
			}
    });
}

function visualizarNotificacoes(){
	$.ajax({
        url: "funcoes/visualizarNotificacoes.php",
        type: "POST"
    });
}
function rolarConteudo(){
	$("#body_chat").animate({ scrollTop: $("#body_chat")[0].scrollHeight }, 0);
}

function listarUltimaMensagem(){
	var Ids = new Array();
	$('.conversa').each(function(){
		Ids.push($(this).prop('id'));
	});
	$.ajax({
        url: "funcoes/retornarUltimaMensagem.php",
        type: "POST",
        data: {Ids:Ids},
        async: false,
            success : function(e){
                var json = JSON.parse(e);
                // console.log(json);
                $(".horario_msg").html("");
                $(".texto").html("");
					for(i = 0; i < json["quantidade"]; i++){
                		$('#hora'+json[i][0]).append('<p>'+json[i][1][1])+'</p>';
						$('#texto'+json[i][0]).append('<p>'+json[i][1][0])+'</p>';
					}
        	}
    });
}

// function listarMensagem(destinatario){

// 	$.ajax({
//         url: "funcoes/retornarMensagens.php",
//         type: "POST",
//         data: {Destinatario:destinatario},
//         async: false,
//             success : function(e){
//                 var json = JSON.parse(e);
//                 console.log(json);
//                 $("#body_chat").html("");
//                 // $(".conversa").css('background', 'rgba(55,169,191,0.2)');
//                 // $("#"+destinatario).css('background', 'rgba(55,169,191,0.4)');
//                 json.forEach(function(o, index){
//                 // console.log(o);
//      				if (o['remetente'] != destinatario) {
//      					if (o['status'] == 1) {
//      						$("#body_chat").append('<div class="mensagem_direita mt-1"><div class="box_direita"><div class="texto_direita"><p id="msgDireita">'+o[1]+'</p></div><div class="status_msg"><i class="far fa-eye"></i></div><div class="horario_envio_mensagem_direita">'+o[2]+'</div></div></div>');
//      					}
//      					else{
//      						$("#body_chat").append('<div class="mensagem_direita mt-1"><div class="box_direita"><div class="texto_direita"><p id="msgDireita">'+o[1]+'</p></div><div class="horario_envio_mensagem_direita">'+o[2]+'</div></div></div>');
//      					}
//      				}
//      				else{
// 						$("#body_chat").append('<div class="mensagem_esquerda container mt-1"><div class="box_esquerda"><div class="texto_esquerdo"><p>'+o[1]+'</p></div><div class="horario_envio_mensagem">'+o[2]+'</div></div></div>');
//      				}
//       	        });
//         	}
//     });
//   	$("#txtMensagem").focus();
  	

//     visualizarMensagens(destinatario);
//     // rolarConteudo();
//     listarUltimaMensagem();
//     retornarQtdMensagensNaoLidas();
// }
	var scrollBottom = 0;
	var scroll = 0;

$(document).ready(function() {
	 usuarioLogado = $("#idUsuarioLogado").val();
	retornarQtdNotificacoes();
	retornarTotaldeMensagensNaoLidas();
	listarConversasRecentes();
	listarUltimaMensagem();
	atualizarDados();
	retornarQtdMensagensNaoLidas();

	$(document).on("click", '.conversa', function(){
		let idDestinatario = $(this).attr('id');
		idDes = idDestinatario;
		listarMensagem(idDestinatario);
		atualizarMensagens();
		retonarDadosDestinatario(idDestinatario);
		visualizarMensagens(idDestinatario);
		retornarTotaldeMensagensNaoLidas();
		rolarConteudo();
		$("#cabecalho").css('display', 'block');
		$("#entrada").css('display', 'block');
		$("#txtMensagem").val("");
		scrollBottom = $("#body_chat").scrollTop() + $("#body_chat").height();
		$(".btnRolarConteudo").prop('id', 'btnRolarConteudo');
		$("#btnRolarConteudo").prop('id', 'btnRolarConteudo'+idDes);
	});

	$(document).on("click", '#btnEnviarMsg', function(){
		let idDestinatario = $("#idDestinatario").val();
		let msg = $("#txtMensagem").val();
		if (msg != "") {
			enviarMensagem(msg,idDestinatario);
			listarMensagem(idDestinatario);
			visualizarMensagens(idDestinatario);
			rolarConteudo();
    		listarUltimaMensagem();
    		listarConversasRecentes();
    		$("#txtMensagem").val("");
		}
	});

	$(document).on("keydown", function(e){
		if (e.keyCode === 13) {
			$("#btnEnviarMsg").click();
		}
	});

	$(document).on("click", '.contatos-i', function(){
		let idDestinatario = $(this).attr('id');
		idDes = idDestinatario;
		atualizarMensagens();
		listarMensagem(idDestinatario);
		let msg = "";
		enviarMensagem(msg,idDestinatario);
		listarMensagem(idDestinatario);
		retonarDadosDestinatario(idDestinatario);
		visualizarMensagens(idDestinatario);

		$("#cabecalho").css('display', 'block');
		$("#entrada").css('display', 'block');
		$("#txtMensagem").val("");
		$("#menu_recentes").css('display', 'block');
		$("#menu_contatos").css('display', 'none');
		$("#menu_onlines").css('display', 'none');
		removerNegrito();
		$("#listarRecentes").css('font-weight', 'bold');

		retornarTotaldeMensagensNaoLidas();
		rolarConteudo();
		$("#cabecalho").css('display', 'block');
		$("#entrada").css('display', 'block');
		$("#txtMensagem").val("");
		scrollBottom = $("#body_chat").scrollTop() + $("#body_chat").height();
		$(".btnRolarConteudo").prop('id', 'btnRolarConteudo');
		$("#btnRolarConteudo").prop('id', 'btnRolarConteudo'+idDes);
	});
	
	$(document).on("click", '#btnNotificacao', function(){
		visualizarNotificacoes();
	});
	

	$("#body_chat").scroll(function(){
		scroll = $(this).scrollTop() + $("#body_chat").height();
		// alert("Funfando");
		// alert($(this).scrollTop());
        if (scroll < scrollBottom) {
            $('.btnRolarConteudo').fadeIn();
        } else {
            $('.btnRolarConteudo').fadeOut();
        }
    });

    $('#btnRolarConteudo').click(function(){
    	visualizarMensagens(idDes);
        $("#body_chat").animate({ scrollTop: $("#body_chat")[0].scrollHeight }, 500);
		scroll = $(this).scrollTop() + $("#body_chat").height();
    });
});


// FUNÇOES SIGNIFICADOS

// enviarMensagem(msg,idDestinatario); -> envia a mensagem pro destinatario
// listarMensagem(idDestinatario); -> lista as mensagens da conversa
// listarUltimaMensagem(); -> lista a ultima mensagem que o usuario enviou ou recebeu
// retonarDadosDestinatario(idDestinatario); -> retorna os dados de um usuarios
// visualizarMensagens(idDestinatario); -> visualiza as mensagens que nao foram lidas
// atualizarMensagens(); -> atualiza as mensagens em tempo real
// retornarTotaldeMensagensNaoLidas() -> retorna a quantidade de msg que o usuario logado nao leu
// listarConversasRecentes() -> lista as conversas do usuario logado