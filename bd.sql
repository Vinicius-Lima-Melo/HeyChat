create database heychat;
use heychat; 

create table usuario(
	idUsuario int primary key auto_increment,
	nome varchar(255),
	apelido varchar(30),
	fotoPerfil varchar(255),
	email varchar(70),
	senha varchar(200),
	endereco varchar(60),
	bairro varchar(60),
	cidade varchar(60),
	estado char(2),
	cep int(8),
	status int(1),
	ultimoAcesso timestamp
);

create table conversa(
	idConversa int primary key auto_increment,
	usuario1 int,
	usuario2 int,
	ultimaMensagem timestamp,
	foreign key (usuario1) references usuario(idUsuario),
	foreign key (usuario2) references usuario(idUsuario)
);

create table mensagem(
	idMensagem int primary key auto_increment,
	texto varchar(1500),
	horarioEnvio timestamp,
	remetente int,
	destinatario int,
	conversa int,
	status int(1),
	foreign key (remetente) references usuario(idUsuario),
	foreign key (destinatario) references usuario(idUsuario),
	foreign key (conversa) references conversa(idConversa)
);

create table notificacao(
	idNotificacao int primary key auto_increment,
	usuario int,
	mensagem varchar(60),
	status int,
	foreign key (usuario) references usuario(idUsuario)
);

create table amizade(
	idAmizade int primary key auto_increment,
	remetente int,
	destinatario int,
	status int(1),
	foreign key (remetente) references usuario(idUsuario),
	foreign key (destinatario) references usuario(idUsuario)
);

