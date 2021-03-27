function removerNegrito(){
	$("#listarRecentes").css('font-weight', 'normal');
	$("#listarOnlines").css('font-weight', 'normal');
	$("#listarContatos").css('font-weight', 'normal');
}
var scrollBottom = 0;
var scroll = 0;
var qtdNaoLidas = 0;
$(document).ready(function() {
	var socket =  io.connect('http://localhost:3000');
	var usuarioLogado = $("#idUsuarioLogado").val();
	var destinatario = "";
	$("#usuarioLogado").html(usuarioLogado);

	socket.on('novaMensagem', function(dados){
		socket.emit('totalMensagensNaoLidas', dados.destinatario);
		if (destinatario == dados.remetente && usuarioLogado == dados.remetente ) {
		// se o selecionado for igual ao usuario que enviou a msg
			$("#body_chat").append('<div class="mensagem_direita mt-1"><div class="box_direita"><div class="texto_direita"><p id="msgDireita">'+dados['msg']+'oiiii</p></div><div class="status_msg"><i class="far fa-eye"></i></div><div class="horario_envio_mensagem_direita">'+dados['horarioEnvio']+'</div></div></div>');
			rolarConteudo();
		}
		else if (destinatario == dados.remetente && usuarioLogado != dados.remetente) {
			// conversa aberta
			$("#body_chat").append('<div class="mensagem_esquerda container mt-1"><div class="box_esquerda"><div class="texto_esquerdo"><p>'+dados['msg']+'</p></div><div class="horario_envio_mensagem">'+dados['horarioEnvio']+'</div></div></div>');
			rolarConteudo();
			socket.emit('listarConversas', usuarioLogado);
		}
		else{
			// conversa fechada
			socket.emit('listarConversas', usuarioLogado);
		}
	});


function rolarConteudo(){
	$("#body_chat").animate({ scrollTop: $("#body_chat")[0].scrollHeight }, 500);
	socket.emit('visualizarMensagens', usuarioLogado, destinatario);
}

function listarMensagens(){

	// socket.emit('visualizarMensagens', usuarioLogado, destinatario);
	socket.emit('totalMensagensNaoLidas', usuarioLogado);
	socket.emit('totalMensagensNaoLidasIndividual', usuarioLogado);
	socket.emit('listarMensagens', usuarioLogado, destinatario);
	
    // socket.emit('totalMensagensNaoLidasIndividual', usuarioLogado);

}

	socket.emit('usuario_conectado', usuarioLogado);
	socket.emit('listarConversas', usuarioLogado);
	socket.emit('totalMensagensNaoLidas', usuarioLogado);
	
	

    $(document).on("click", '.conversa', function(){
		let idDestinatario = $(this).attr('id');
		destinatario = idDestinatario;
		$("#usuarioSelecionado").html(destinatario);
        

		$("#cabecalho").css('display', 'block');
		$("#entrada").css('display', 'block');
		$("#txtMensagem").val("");
		scrollBottom = $("#body_chat").scrollTop() + $("#body_chat").height();
		$(".btnRolarConteudo").prop('id', 'btnRolarConteudo');
		$("#btnRolarConteudo").prop('id', 'btnRolarConteudo'+destinatario);
		
		socket.emit('totalMensagensNaoLidasIndividual', usuarioLogado);
		socket.emit('usuarioSelecionado', destinatario);
		// socket.emit('listarMensagens', usuarioLogado, destinatario);
		listarMensagens();

		$("#txtMensagem").focus();
		
	});

    $(document).on("click", '#btnEnviarMsg', function(){
		

		let idDestinatario = $("#idDestinatario").val();
		let msg = $("#txtMensagem").val();
		if (msg != "") {
			socket.emit('cadastrarMensagem', usuarioLogado, idDestinatario, msg);
			// socket.emit('listarMensagens', usuarioLogado, destinatario);
			listarMensagens();
			socket.emit('listarConversas', usuarioLogado);
			$("#txtMensagem").val("");
			$("#txtMensagem").focus();
			rolarConteudo();
		}
	});

	$(document).on("keydown", function(e){
		if (e.keyCode === 13) {
			$("#btnEnviarMsg").click();
		}
	});

    socket.on("getMensagens", function(dados){
    	$("#body_chat").html("");
    	if (dados.length != 0) {
	    	$("#body_chat").html("");
			for(i = 0; i < dados.length; i++){
				if (dados[i]['remetente'] != destinatario) {
					if (dados[i]['status'] == 1) {
						$("#body_chat").append('<div class="mensagem_direita mt-1"><div class="box_direita"><div class="texto_direita"><p id="msgDireita">'+dados[i]['texto']+'</p></div><div class="status_msg"><i class="far fa-eye"></i></div><div class="horario_envio_mensagem_direita">'+dados[i]['horarioEnvio']+'</div></div></div>');
					}
					else{
						$("#body_chat").append('<div class="mensagem_direita mt-1"><div class="box_direita"><div class="texto_direita"><p id="msgDireita">'+dados[i]['texto']+'</p></div><div class="status_msg"></div><div class="horario_envio_mensagem_direita">'+dados[i]['horarioEnvio']+'</div></div></div>');
					}
				}
				else{
					$("#body_chat").append('<div class="mensagem_esquerda container mt-1"><div class="box_esquerda"><div class="texto_esquerdo"><p>'+dados[i]['texto']+'</p></div><div class="horario_envio_mensagem">'+dados[i]['horarioEnvio']+'</div></div></div>');
				}
			}
			rolarConteudo();
		}
		else{
			$("#body_chat").append('<div class="alert alert-primary text-center" role="alert">Envie uma mensagem para iniciar uma conversa agora mesmo!!</div>');
		}
    });

    $("#body_chat").scroll(function(){
		scroll = $(this).scrollTop() + $("#body_chat").height();
        if (scroll < scrollBottom) {
            $('.btnRolarConteudo').fadeIn();
        } else {
            $('.btnRolarConteudo').fadeOut();
        }
    });

    $('#btnRolarConteudo').click(function(){
        rolarConteudo();
		scroll = $(this).scrollTop() + $("#body_chat").height();
    });

	socket.on('getConversas', function(dados){
		$("#menu_recentes").html("");
		var ids = [];
		for(i = 0; i < dados.length; i++){
			$("#menu_recentes").append('<div class="conversa" id="'+dados[i]['idUsuario']+'"><div class="img_usuario"><img src="imgUsuarios/'+dados[i]['fotoPerfil']+'"></div><div class="nome_usuario"><p>'+dados[i]['apelido']+'</p></div><div class="horario_msg" id="hora'+dados[i]['idUsuario']+'"></div><div class="mensagem_conversa"><div class="texto" id="texto'+dados[i]['idUsuario']+'"></div><div class="qtd_msg" style="display:none;" id="qtd'+dados[i]['idUsuario']+'"></div></div></div></div>');
			ids.push(dados[i]['idUsuario']);
		}
        socket.emit('ultima_mensagem', ids, usuarioLogado);
        socket.emit('totalMensagensNaoLidasIndividual', usuarioLogado);
	});

	socket.on('getUltimasMensagens', function(dados){
		$(".horario_msg").html("");
	    $(".texto").html("");
		for(i = 0; i < dados.length; i++){
			if (dados[i]["destinatario"] == usuarioLogado) {
				$('#hora'+dados[i]["remetente"]).append('<p>'+dados[i]["horarioEnvio"])+'</p>';
				$('#texto'+dados[i]["remetente"]).append('<p>'+dados[i]["texto"])+'</p>';
			}
			else{
				$('#hora'+dados[i]["destinatario"]).append('<p>'+dados[i]["horarioEnvio"])+'</p>';
				$('#texto'+dados[i]["destinatario"]).append('<p>'+dados[i]["texto"])+'</p>';
			}
		}
	});

	socket.on('getDadosUsuario', function(dados){
		$("#destinatario").html("");
        $("#idDestinatario").html("");
        $("#img_usuario_chat").html('');
        $("#status_usuario_chat").html('');
        $("#destinatario").html(dados['apelido']);
        $("#idDestinatario").val(dados['idUsuario']);
        $("#img_usuario_chat").append('<img src="imgUsuarios/'+dados['fotoPerfil']+'">');
        if (dados['status'] == 1) {
        	$("#status_usuario_chat").append('<p>Online</p>');
        }
        else{
        	$("#status_usuario_chat").append('<p>'+dados['ultimoAcesso']+'</p>');
        }
	});


	// NOTIFICAÇOES

	
	socket.on('getTotalMensagensNaoLidas', function(dados){
		if (dados.total != 0) {
			$(document).prop('title', "("+dados.total+") Hey Chat | "+dados.apelido+"");
		}
		else{
			$(document).prop('title', "Hey Chat | "+dados.apelido+"");
		}
	});
	socket.on('getMensagensNaoLidas', function(dados){
		if (dados.length == 0) {
				$('.qtd_msg').html("");
				$('.qtd_msg').css('display', 'none');
                $('.texto').css('font-weight', 'normal');
		}
		else{
			for(i = 0; i < dados.length; i++){
				if (dados[i]['qtd'] == 0) {
					$('#qtd'+dados[i]['remetente']).css('display', 'none');
	                $('#texto'+dados[i]['remetente']).css('font-weight', 'normal');
				}
				else{
					$('#qtd'+dados[i]['remetente']).html("");
					$('#qtd'+dados[i]['remetente']).append('<p>'+dados[i]['qtd'])+'</p>';
					$('#qtd'+dados[i]['remetente']).css('display', 'block');
					$('#texto'+dados[i]['remetente']).css('font-weight', 'bold');	
				}
			}
		}
	});



























































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