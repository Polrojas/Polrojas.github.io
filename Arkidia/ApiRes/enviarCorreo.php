<?php  
include "config.php";
include "utils.php";
$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$usuario= $_POST['usuario'];
    $sql = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");
    $sql->bindParam(':usuario', $usuario);    
    $sql->execute();
    $fila_hijo= $sql->fetch(PDO::FETCH_ASSOC);

    $sql = $dbConn->prepare("SELECT * FROM usuario_padre where mail=:mail");
    $sql->bindParam(':mail', $usuario);    
    $sql->execute();
    $fila_padre= $sql->fetch(PDO::FETCH_ASSOC);

    $sql = $dbConn->prepare("SELECT * FROM usuario_administrador where mail=:mail");
    $sql->bindParam(':mail', $usuario);    
    $sql->execute();
    $fila_administrador= $sql->fetch(PDO::FETCH_ASSOC);

    if ($fila_padre){ 
		$nombre = $fila_padre['nombre'];
		enviaCorreo($usuario, $nombre);
    }elseif($fila_administrador){
		$nombre = $fila_administrador['nombre'];
		enviaCorreo($usuario, $nombre);    	
    }elseif($fila_hijo){
 		$respuesta['resultado']="ERROR";
		$respuesta['mensaje']="Solicitale a gran Arkidian que te cambie la clave.";
		echo json_encode(  $respuesta  );
		exit();   	
    }else{
 		$respuesta['resultado']="ERROR";
		$respuesta['mensaje']="El usuario no se encuentra registrado.";
		echo json_encode(  $respuesta  );
		exit();   	    	
    }
		$respuesta['resultado']="OK";
		$respuesta['mensaje']="";
		echo json_encode(  $respuesta  );
		exit();           
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>