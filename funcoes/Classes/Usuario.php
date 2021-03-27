<?php

include_once 'Conexao.php';

class Usuario 
{
	private $idUsuario;
	private $nome;
	private $apelido;
	private $fotoPerfil;
	private $email;
	private $senha;
	private $endereco;
	private $bairro;
	private $cidade;
	private $estado;
	private $cep;
	private $status;
	private $ultimoAcesso;
	private $objConexao;

	function __construct()
	{
		$this->idUsuario = 0;
		$this->nome = "";
		$this->apelido = "";
		$this->fotoPerfil = "";
		$this->email = "";
		$this->senha = "";
		$this->endereco = "";
		$this->bairro = "";
		$this->cidade = "";
		$this->estado = "";
		$this->cep = "";
		$this->status = 0;
		date_default_timezone_set('America/Sao_Paulo');
		$this->ultimoAcesso = '';
		$this->objConexao = new Conexao();
	}

	function setIdUsuario($idUsuario){
		$this->idUsuario = $idUsuario;
	}
	function getIdUsuario(){
		return $this->idUsuario;
	}

	function setNome($nome){
		$this->nome = $nome;
	}
	function getNome(){
		return $this->nome;
	}

	function setApelido($apelido){
		$this->apelido = $apelido;
	}
	function getApelido(){
		return $this->apelido;
	}

	function setFotoPerfil($fotoPerfil){
		$this->fotoPerfil = $fotoPerfil;
	}
	function getFotoPerfil(){
		return $this->fotoPerfil;
	}

	function setEmail($email){
		$this->email = $email;
	}
	function getEmail(){
		return $this->email;
	}

	function setSenha($senha){
		$this->senha = $senha;
	}
	function getSenha(){
		return $this->senha;
	}

	function setEndereco($endereco){
		$this->endereco = $endereco;
	}
	function getEndereco(){
		return $this->endereco;
	}

	function setBairro($bairro){
		$this->bairro = $bairro;
	}
	function getBairro(){
		return $this->bairro;
	}

	function setCidade($cidade){
		$this->cidade = $cidade;
	}
	function getCidade(){
		return $this->cidade;
	}

	function setEstado($estado){
		$this->estado = $estado;
	}
	function getEstado(){
		return $this->estado;
	}

	function setCep($cep){
		$this->cep = $cep;
	}
	function getCep(){
		return $this->cep;
	}

	function setStatus($status){
		$this->status = $status;
	}
	function getStatus(){
		return $this->status;
	}

	function setUltimoAcesso($ultimoAcesso){
		$this->ultimoAcesso = $ultimoAcesso;
	}
	function getUltimoAcesso(){
		return $this->ultimoAcesso;
	}

	// MÉTODO CADASTRAR USUARIO

	function cadastrarUsuario(){
		$conexao = $this->objConexao->conectar();
		$sqlInsert = "INSERT INTO usuario VALUES(0, :nome, :apelido, :fotoPerfil, :email, sha1(:senha), :endereco, :bairro, :cidade, :estado, :cep, :status, :ultimoAcesso)";
		$comando = $conexao->prepare($sqlInsert);
		$comando->bindParam(':nome', $this->nome);
		$comando->bindParam(':apelido', $this->apelido);
		$comando->bindParam(':fotoPerfil', $this->fotoPerfil);
		$comando->bindParam(':email', $this->email);
		$comando->bindParam(':senha', $this->senha);
		$comando->bindParam(':endereco', $this->endereco);
		$comando->bindParam(':bairro', $this->bairro);
		$comando->bindParam(':cidade', $this->cidade);
		$comando->bindParam(':estado', $this->estado);
		$comando->bindParam(':cep', $this->cep);
		$comando->bindParam(':status', $this->status);
		$comando->bindParam(':ultimoAcesso', $this->ultimoAcesso);
		$comando->execute();
	}

	function logarUsuario($email,$senha){
		$conexao = $this->objConexao->conectar();
		$sqlSelect= "SELECT * FROM usuario WHERE email = :email and senha = sha1(:senha)";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':email', $email);
		$consulta->bindParam(':senha', $senha);
		$consulta->execute();

		if ($consulta->rowCount() > 0){
        	$sqlUpdate = "UPDATE usuario SET status = 1, ultimoAcesso = '' WHERE email = :email and senha = sha1(:senha)";
			$comando = $conexao->prepare($sqlUpdate);
			$comando->bindParam(':email', $email);
			$comando->bindParam(':senha', $senha);
			$comando->execute();
			return true;
    	}
    	else{
    		return false;
    	}
	}
	function retornarUsuarioLogado($email){
		$conexao = $this->objConexao->conectar();
		$sqlSelect= "SELECT * FROM usuario WHERE email = :email";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':email', $email);
		$consulta->execute();
		return $consulta->fetch();
	}
	function deslogarUsuario($email){
		date_default_timezone_set('America/Sao_Paulo');
		$timestamp = date("Y-m-d H:i:s");
		$conexao = $this->objConexao->conectar();
		$sqlUpdate = "UPDATE usuario SET status = 0, ultimoAcesso = :ultimoAcesso  WHERE email = :email";
		$comando = $conexao->prepare($sqlUpdate);
		$comando->bindParam(':ultimoAcesso', $timestamp);
		$comando->bindParam(':email', $email);
		$comando->execute();
	}

	function atualizarUsuario($email){
		$conexao = $this->objConexao->conectar();
		$sqlInsert = "UPDATE usuario SET nome = :nome, apelido = :apelido, fotoPerfil = :fotoPerfil, senha = sha1(:senha), endereco = :endereco, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep where email = :email";
		$comando = $conexao->prepare($sqlInsert);
		$comando->bindParam(':nome', $this->nome);
		$comando->bindParam(':apelido', $this->apelido);
		$comando->bindParam(':fotoPerfil', $this->fotoPerfil);
		$comando->bindParam(':email', $email);
		$comando->bindParam(':senha', $this->senha);
		$comando->bindParam(':endereco', $this->endereco);
		$comando->bindParam(':bairro', $this->bairro);
		$comando->bindParam(':cidade', $this->cidade);
		$comando->bindParam(':estado', $this->estado);
		$comando->bindParam(':cep', $this->cep);
		$comando->execute();
	}

	function listarConversasRecentes($id){
		$conexao = $this->objConexao->conectar();
		$sqlSelect= "SELECT * FROM conversa WHERE usuario1 = :id OR usuario2 = :id order by ultimaMensagem desc";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $id);
		$consulta->execute();
		return $consulta->fetchAll();
	}
		
	function retornarQtdConversas($id){
		$conexao = $this->objConexao->conectar();
		$sqlSelect= "SELECT * from conversa WHERE usuario1 = :id or usuario2 = :id";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $id);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	function retornarQtdMensagens($destinatario,$remetente){
		$conexao = $this->objConexao->conectar();
		$sqlSelect= "SELECT count(idMensagem) from mensagem where remetente = :remetente and destinatario = :destinatario and status = 0";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':remetente', $remetente);
		$consulta->bindParam(':destinatario', $destinatario);
		$consulta->execute();
		return $consulta->fetch();
	}
	
	function visualizarMensagens($destinatario,$remetente){
		$conexao = $this->objConexao->conectar();
		$sqlUpdate= "UPDATE mensagem SET status = 1 where remetente = :remetente and destinatario = :destinatario";
		$consulta = $conexao->prepare($sqlUpdate);
		$consulta->bindParam(':remetente', $remetente);
		$consulta->bindParam(':destinatario', $destinatario);
		$consulta->execute();
	}

	function listarUsuariosOnline($id){
		$conexao = $this->objConexao->conectar();
		$sqlSelect= "SELECT * FROM usuario WHERE status = 1 and idUsuario != :id";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $id);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	function listarContatos($id){
		$conexao = $this->objConexao->conectar();
		$sqlSelect= "SELECT * FROM usuario WHERE idUsuario != :id";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $id);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	function validarEmail($email){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT email FROM usuario WHERE email = :email";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':email', $email);
		$consulta->execute();

		if ($consulta->rowCount() > 0) {
			return false;
		}
		else{
			return true;
		}

	}

	function validarSenha($senha,$email){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT email FROM usuario WHERE email = :email and senha = sha1(:senha)";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':email', $email);
		$consulta->bindParam(':senha', $senha);
		$consulta->execute();

		if ($consulta->rowCount() > 0) {
			return false;
		}
		else{
			return true;
		}

	}

	function cadastrarConversa($remetente,$destinatario){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT * FROM conversa WHERE usuario1 = :remetente and usuario2 = :destinatario or  usuario1 = :destinatario and usuario2 = :remetente";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':remetente', $remetente);
		$consulta->bindParam(':destinatario', $destinatario);
		$consulta->execute();

		if ($consulta->rowCount() > 0) {
			return false;
		}
		else{
			$sqlInsert = "INSERT INTO conversa values(0,:remetente,:destinatario,0)";
			$comando = $conexao->prepare($sqlInsert);
			$comando->bindParam(':remetente', $remetente);
			$comando->bindParam(':destinatario', $destinatario);
			$comando->execute();
			return true;
		}

	}

	function retornarIdConversa($remetente, $destinatario){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT * FROM conversa WHERE usuario1 = :remetente and usuario2 = :destinatario or  usuario1 = :destinatario and usuario2 = :remetente";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':remetente', $remetente);
		$consulta->bindParam(':destinatario', $destinatario);
		$consulta->execute();
		return $consulta->fetch();
	}

	function retornarDadosDoUsuario($id){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT idUsuario,nome,apelido,fotoPerfil,email,status, Time_Format(ultimoAcesso,'%H:%i') as ultimoAcesso from usuario WHERE idUsuario = :id limit 1";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $id);
		$consulta->execute();
		return $consulta->fetch(PDO::FETCH_ASSOC);
	}

	function retornarDadosSimplesDoUsuario($id){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT idUsuario, apelido, fotoPerfil, cidade, estado from usuario where idUsuario = :id";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $id);
		$consulta->execute();
		return $consulta->fetch();
	}

	function cadastrarMensagem($remetente,$destinatario,$mensagem,$conversa){
		$conexao = $this->objConexao->conectar();
		date_default_timezone_set('America/Sao_Paulo');
		$timestamp = date("Y-m-d H:i:s");
		$sqlInsert = "INSERT INTO mensagem(texto,horarioEnvio,remetente,destinatario,conversa,status) values('$mensagem',:horario,:remetente, :destinatario, :conversa,0)";
		$comando = $conexao->prepare($sqlInsert);
		$comando->bindParam(':remetente', $remetente);
		$comando->bindParam(':destinatario', $destinatario);
		$comando->bindParam(':conversa', $conversa);
		$comando->bindParam(':horario', $timestamp);
		$comando->execute();

		$Update = "UPDATE conversa SET ultimaMensagem = :horario where usuario1 = :remetente and usuario2 = :destinatario or usuario2 = :remetente and usuario1 = :destinatario";
		$comando = $conexao->prepare($Update);
		$comando->bindParam(':remetente', $remetente);
		$comando->bindParam(':destinatario', $destinatario);
		$comando->bindParam(':horario', $timestamp);
		$comando->execute();
	}

	function listarMensagens($usuarioLogado, $destinatario){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT idMensagem, texto, time_format(horarioEnvio,'%H:%i'),remetente,destinatario,conversa, status from mensagem where remetente = :usuarioLogado and destinatario = :destinatario or destinatario = :usuarioLogado and remetente = :destinatario order by idMensagem asc ";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':usuarioLogado', $usuarioLogado);
		$comando->bindParam(':destinatario', $destinatario);
		$comando->execute();
		return $comando->fetchAll();
	}

	function listarUltimaMensagem($usuarioLogado, $destinatario){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT texto, time_format(horarioEnvio,'%H:%i'),idMensagem from mensagem where remetente = :usuarioLogado and destinatario = :destinatario or destinatario = :usuarioLogado and remetente = :destinatario order by idMensagem desc limit 1";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':usuarioLogado', $usuarioLogado);
		$comando->bindParam(':destinatario', $destinatario);
		$comando->execute();
		return $comando->fetch();
	}

	function retornarTotaldeMensagensNaoLidas($usuarioLogado){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT count(idMensagem) from mensagem where destinatario = :id and status = 0";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $usuarioLogado);
		$consulta->execute();
		return $consulta->fetch();
	}

	function fecharConexao(){
		$conexao = $this->objConexao->conectar();
		$conexao = null;
	}

	function filtrarUsuarios($filtro, $id){
		$conexao = $this->objConexao->conectar();
		$filtro = "%".$filtro."%";
		$sqlSelect = "SELECT idUsuario, apelido, fotoPerfil, cidade, estado from usuario WHERE idUsuario != :id and apelido LIKE :apelido";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':apelido', $filtro);
		$comando->bindParam(':id', $id);
		$comando->execute();
		return $comando->fetchAll();
	}

	function listarUsuarios($id){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT idUsuario, apelido, fotoPerfil, cidade, estado FROM usuario WHERE idUsuario != :id";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $id);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	function cadastrarAmizade($usuarioLogado, $destinatario){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT * from amizade where remetente = :remetente and destinatario = :destinatario or remetente = :destinatario and destinatario = :remetente";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':remetente', $usuarioLogado);
		$comando->bindParam(':destinatario', $destinatario);
		$comando->execute();
		if ($comando->rowCount() > 0) {
		}
		else{
			$conexao = $this->objConexao->conectar();
			$sqlInsert = "INSERT INTO amizade VALUES(0,:remetente,:destinatario,1)";
			$comando = $conexao->prepare($sqlInsert);
			$comando->bindParam(':remetente', $usuarioLogado);
			$comando->bindParam(':destinatario', $destinatario);
			$comando->execute();
			///////// Pegar dados
			$sqlSelect = "SELECT apelido FROM usuario where idUsuario = :remetente";
			$comando = $conexao->prepare($sqlSelect);
			$comando->bindParam(':remetente', $usuarioLogado);
			$comando->execute();
			$retorno = $comando->fetch();
			///////// Cadastrar 
			$mensagem = $retorno[0]." enviou uma solicitação";
			$sql = "INSERT INTO notificacao values(0,:destinatario, '$mensagem', 0)";
			$comando = $conexao->prepare($sql);
			$comando->bindParam(':destinatario', $destinatario);
			$comando->execute();
		}
	}

	function retornarQtdNotificacoesNaoVistas($usuarioLogado){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT count(idNotificacao) FROM notificacao where usuario = :usuarioLogado and status = 0";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':usuarioLogado', $usuarioLogado);
		$comando->execute();
		return $comando->fetch();
	}

	function retornarQtdNotificacoes($usuarioLogado){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT count(idNotificacao) FROM notificacao where usuario = :usuarioLogado";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':usuarioLogado', $usuarioLogado);
		$comando->execute();
		return $comando->fetch();
	}

	function visualizarNotificacoes($usuarioLogado){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "UPDATE notificacao SET status = 1 where usuario = :usuarioLogado";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':usuarioLogado', $usuarioLogado);
		$comando->execute();
		return $comando;
	}

	function retornarNotificacoes($usuarioLogado){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT idNotificacao, mensagem FROM notificacao where usuario = :usuarioLogado order by idNotificacao desc";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':usuarioLogado', $usuarioLogado);
		$comando->execute();
		return $comando->fetchAll();
	}

	function responderSolicitacao($usuarioLogado, $destinatario, $op){
		$conexao = $this->objConexao->conectar();
		if ($op == 0) {
			$sqlUpdate = "UPDATE amizade SET status = 0 where remetente = :remetente and destinatario = :destinatario or remetente = :destinatario and destinatario = :remetente";
			///////// Pegar dados
			$sqlSelect = "SELECT apelido FROM usuario where idUsuario = :remetente";
			$comando = $conexao->prepare($sqlSelect);
			$comando->bindParam(':remetente', $usuarioLogado);
			$comando->execute();
			$retorno = $comando->fetch();
			///////// Cadastrar 
			$mensagem = $retorno[0]." negou sua solicitação";
			$sql = "INSERT INTO notificacao values(0,:destinatario, '$mensagem', 0)";
			$comando = $conexao->prepare($sql);
			$comando->bindParam(':destinatario', $destinatario);
			$comando->execute();
		}
		else{
			$sqlUpdate = "UPDATE amizade SET status = 2 where remetente = :remetente and destinatario = :destinatario or remetente = :destinatario and destinatario = :remetente";
			///////// Pegar dados
			$sqlSelect = "SELECT apelido FROM usuario where idUsuario = :remetente";
			$comando = $conexao->prepare($sqlSelect);
			$comando->bindParam(':remetente', $usuarioLogado);
			$comando->execute();
			$retorno = $comando->fetch();
			///////// Cadastrar 
			$mensagem = $retorno[0]." aceitou sua solicitação";
			$sql = "INSERT INTO notificacao values(0,:destinatario, '$mensagem', 0)";
			$comando = $conexao->prepare($sql);
			$comando->bindParam(':destinatario', $destinatario);
			$comando->execute();
		}
		$comando = $conexao->prepare($sqlUpdate);
		$comando->bindParam(':remetente', $usuarioLogado);
		$comando->bindParam(':destinatario', $destinatario);
		$comando->execute();
	}

	function verificarAmizade($usuarioLogado, $destinatario){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT status, remetente, destinatario from amizade where remetente = :usuarioLogado and destinatario = :destinatario or destinatario = :usuarioLogado and remetente = :destinatario order by idAmizade desc limit 1";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':usuarioLogado', $usuarioLogado);
		$comando->bindParam(':destinatario', $destinatario);
		$comando->execute();
		return $comando->fetch();
	}
	function validarAmizade($usuarioLogado, $destinatario){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT status, remetente, destinatario from amizade where remetente = :usuarioLogado and destinatario = :destinatario and status = 2 or destinatario = :usuarioLogado and remetente = :destinatario and status = 2 limit 1";
		$comando = $conexao->prepare($sqlSelect);
		$comando->bindParam(':usuarioLogado', $usuarioLogado);
		$comando->bindParam(':destinatario', $destinatario);
		$comando->execute();
		if ($comando->rowCount() > 0) {
			return true;
		}
		else if(is_null($comando) || $comando->rowCount() == 0){
			return false;
		}
	}

	function retornarAmigos($usuarioLogado){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT remetente, destinatario from amizade where remetente = :id and status = 2 or destinatario = :id and status = 2";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $usuarioLogado);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	function retornarSolicitacoes($usuarioLogado){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT remetente from amizade where destinatario = :id and status = 1";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $usuarioLogado);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	function retornarPedidosDeAmizades($usuarioLogado){
		$conexao = $this->objConexao->conectar();
		$sqlSelect = "SELECT destinatario from amizade where remetente = :id and status = 1";
		$consulta = $conexao->prepare($sqlSelect);
		$consulta->bindParam(':id', $usuarioLogado);
		$consulta->execute();
		return $consulta->fetchAll();
	}

}
?>
	