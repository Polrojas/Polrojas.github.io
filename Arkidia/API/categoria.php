<?php
include_once 'db.php';
class Categoria extends BaseDatos{
	function obtenerCategorias(){
		$query = $this->connect()->query('SELECT * FROM categorias');
		return $query;
	}
}
?>