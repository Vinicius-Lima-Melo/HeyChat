<?php

	Class Conexao{

		private $servidor;
		private $bd;
		private $usuario;
		private $senha;

		function __construct()
		{
			$this->servidor = "localhost";
			$this->bd = "heychat";
			$this->usuario = "root";
			$this->senha = "";
		}

		function conectar()
		{
			$conn = new PDO("mysql:host=".$this->servidor.";dbname=".$this->bd,$this->usuario,$this->senha,		
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            return $conn;
		}
	}
?>