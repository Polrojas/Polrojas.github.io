<?php
class ConsultaCategorias{
	private $host;
	private $db;
	private $user;
	private $password;
	private $charset;

	public function __construct(){
		$this->host     = 'localhost';
		$this->db       = 'categorias';
		$this->user     = 'geeknaco';
		$this->password = 'Geeknaco.117.mysql';
		$this->charset  = 'utf8mb4';
	}

	function connect(){
		try{
			$connection = "mysql:host=" . $this->host . "; dbname=" . $this->db;
			$options = [
				PDO::ATTR_ERROMODE                =>PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES        =>false,
			];

			$pdo = new PDO($connection,$this->user,$this->password);
			return $pdo;
		}catch(PDOException $e){
			print_r('Error conexión: ' . $e->getMessage());
		}

	}
}
?>