var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io').listen(server);
var bodyParser = require("body-parser");
var moment = require('moment'); 
moment().format(); 
const mysql = require('mysql');

const con = mysql.createConnection({
	host: 'localhost',
	user: 'root', 
	password: '',
	database: 'heychat' 
});
con.connect((err) => {
	if (err) {
		console.log('Erro na conexao com o banco', err)
		return
	}
	console.log('Conectado com o banco!')
})

usuarios = [];

server.listen(process.env.PORT || 3000);
console.log('Server running...');

app.get('/', function(req, res){
	res.sendFile(__dirname + '/index.html');	
});

io.sockets.on('connection',function(socket){
	
	socket.on("usuario_conectado", function (idUsuario) {
        usuarios['_'+idUsuario] = socket.id;
        console.log("+1 usuario");
        io.emit("usuario_conectado", idUsuario);
    });

	socket.on('listarConversas', function(usuarioLogado){
		var ids = new Array();
		con.query("SELECT * FROM conversa WHERE (usuario1 = '" + usuarioLogado + "' or usuario2 = '" + usuarioLogado + " order by ultimaMensagem desc')", function (error, conversas) {
        	conversas.forEach(function(resultado){
        		if (resultado.usuario1 == usuarioLogado) {
	       			ids.push(resultado.usuario2);
       			}
       			else{
	       			ids.push(resultado.usuario1);
       			}
        	});
		var dadosUsuarios = [];
    	for(let i = 0; i < ids.length; i++){
    		con.query("SELECT idUsuario,nome,apelido,fotoPerfil,email,status, Time_Format(ultimoAcesso,'%H:%i') as ultimoAcesso from usuario where idUsuario = ?", ids[i],function(error,rows){
    			rows.forEach(function(row){
    				dadosUsuarios.push(row);
    			});
				var v = ids.length - 1;
				if (i == v) {
	        		socket.emit('getConversas', dadosUsuarios);
				}
    		});
    	}
		});
	});
	socket.on('ultima_mensagem', function(ids, usuarioLogado){
		var ultimaMensagem = [];
		for (let i = 0; i < ids.length; i++) {
			var destinatario = ids[i];
			con.query("SELECT idMensagem, texto, Time_Format(horarioEnvio,'%H:%i') as horarioEnvio , remetente, destinatario from mensagem where remetente = "+usuarioLogado+" and destinatario = "+destinatario+" or destinatario = "+usuarioLogado+" and remetente = "+destinatario+" order by idMensagem desc limit 1", function(err,rows,fields){
    			if (err) throw err;
    			rows.forEach(function(row){
    				ultimaMensagem.push(row);
    			});
				var v = ids.length - 1;
				if (i == v) {
			       	socket.emit('getUltimasMensagens', ultimaMensagem);
				}
    		});
		}
	});


	socket.on('cadastrarMensagem', function(usuarioLogado, destinatario, msg){
		con.query("SELECT idConversa from conversa where usuario1 = "+usuarioLogado+" and usuario2 = "+destinatario+" or usuario1 = "+destinatario+" and usuario2 = "+usuarioLogado+" limit 1",function(err, rows){
			if (err) throw err;
			var conversa = rows[0].idConversa;
			con.query("INSERT INTO mensagem(texto,horarioEnvio,remetente,destinatario,conversa,status) values('"+msg+"',now(),"+usuarioLogado+", "+destinatario+", "+rows[0].idConversa+",0)", function(err, row){
				if (err) throw err;
				var socketId = usuarios["_"+destinatario];
				let remetente = usuarioLogado;
				let horarioEnvio = moment().format('HH:mm');
				io.to(socketId).emit('novaMensagem', {remetente, destinatario, msg, horarioEnvio});
				con.query("UPDATE conversa SET ultimaMensagem = now() where idConversa ="+conversa+" ",function(){
				});
			});
		});
	});


	socket.on('listarMensagens', function(usuarioLogado, destinatario){
		// SELECT * FROM conversa WHERE usuario1 = 1 and usuario2 = 3 or  usuario1 = 3 and usuario2 = 1
		con.query("SELECT * FROM conversa WHERE usuario1 = "+usuarioLogado+" and usuario2 = "+destinatario+" or  usuario1 = "+destinatario+" and usuario2 = "+usuarioLogado+"",function(err, row){
			if (row.length == 0 ) {
				// console.log("Nao existe a conversa");
				con.query("INSERT INTO conversa values(0,"+usuarioLogado+","+destinatario+",0)",function(){});
				// con.query("INSERT INTO conversa values(0,1,3,0)",function(){});
				socket.emit('getMensagens', row);
			}
			else{
				con.query("SELECT idMensagem, texto, time_format(horarioEnvio,'%H:%i') as horarioEnvio,remetente,destinatario,conversa, status from mensagem where remetente = "+usuarioLogado+" and destinatario = "+destinatario+" or destinatario = "+usuarioLogado+" and remetente = "+destinatario+" order by idMensagem asc", function(err, rows){
					if (err) console.log(err);
					socket.emit('getMensagens', rows);
				});
			}
		});
	});

	socket.on('visualizarMensagens', function(destinatario, remetente){
		// console.log("Visualizando mensagens para o usuario "+destinatario+ " remetente: "+remetente);
		con.query("UPDATE mensagem SET status = 1 where remetente = "+remetente+" and destinatario = "+destinatario+"", function(err, rows){
			// console.log("Mensagens visualizadas");
		});
	});

	socket.on('usuarioSelecionado', function(id){
		con.query("Select idUsuario, apelido, fotoPerfil, status, Time_Format(ultimoAcesso,'%H:%i') as ultimoAcesso from usuario where idUsuario = "+id+"", function(err, rows){
			socket.emit('getDadosUsuario', rows[0]);
		});
	});

	socket.on('totalMensagensNaoLidas', function(usuarioLogado){
		con.query("SELECT u.apelido, count(m.idMensagem) as total from mensagem m inner join usuario u on u.idUsuario = m.destinatario where m.destinatario = "+usuarioLogado+" and m.status = 0;", function(err, row){
			socket.emit('getTotalMensagensNaoLidas', row[0]);
		})
	});
	socket.on('totalMensagensNaoLidasIndividual', function(usuarioLogado){
		con.query("SELECT remetente, count(idMensagem) as qtd from mensagem where destinatario = "+usuarioLogado+" and status = 0 group by conversa", function(err, row){
			socket.emit('getMensagensNaoLidas', row);
			// console.log(row);
		})
	});

	// remover underline
	/*
		var id = idU.substring(1);
	*/

	
	






	// socket.on('enviarMensagem',function(dados){
		
	// 	con.query("INSERT INTO mensagens (remetente, destinatario, mensagem) VALUES ('" + dados.remetente + "', '" + dados.destinatario + "', '" + dados.mensagem + "')", function (error, result) {
	// 		console.log("Mensagem enviada para ", socketId);
 //    	});
	// });

	socket.on('disconnect', function(data){
		// usuarios.splice(usuarios.indexOf(socket.id), 1);

		// console.log("O usuario "+socket.id+ " desconectou");
		// console.log(usuarios);
	});	
});