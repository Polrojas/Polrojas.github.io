<?php
include "config.php";
include "utils.php";

$dbConn =  connect($db);
//Consulta para perfil del padre
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['mail']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM usuario_padre where mail=:mail");
      $sql->bindValue(':mail', $_GET['mail']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
    }
}

// Realiza la registración de un nuevo usuario en la tabla usuario_padre
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
	//////////////////////////////////////
	//Buscamos en la tabla usuario_padre//
	//////////////////////////////////////

	$sql_padre = $dbConn->prepare("SELECT * FROM usuario_padre where mail=:mail");
	$sql_padre->bindParam(':mail', $input['mail']);	
	$sql_padre->setFetchMode(PDO::FETCH_ASSOC);
	$sql_padre->execute();
	$fila_padre= $sql_padre->fetch();
	if(isset($fila_padre['mail']))
	{
		$respuesta['resultado']="ERROR";
    	$respuesta['mensaje']="El usuario ya existe en la tabla.";		
		echo json_encode($respuesta);
		exit();
	}
	elseif($input['nombre'] == "")
	{
		$respuesta['resultado']="ERROR";
    	$respuesta['mensaje']="Debe ingresar el nombre de usuario.";		
		echo json_encode($respuesta);
		exit();
	}
	elseif($input['apellido']=="")
	{
		$respuesta['resultado']="ERROR";
    	$respuesta['mensaje']="Debe ingresar el apellido.";		
		echo json_encode($respuesta);
		exit();
	}
	elseif($input['mail']=="")
	{
		$respuesta['resultado']="ERROR";
    	$respuesta['mensaje']="Debe ingresar una dirección de mail.";		
		echo json_encode($respuesta);
		exit();
	}
	elseif(comprobar_email($input['mail'])==0)
	{
		$respuesta['resultado']="ERROR";
    	$respuesta['mensaje']="El formato del mail es incorrecto.";		
		echo json_encode($respuesta);
		exit();
	}		

	elseif($input['password']=="")
	{
		$respuesta['resultado']="ERROR";
    	$respuesta['mensaje']="Debe ingresar la password.";		
		echo json_encode($respuesta);
		exit();
	}
	elseif(!validar_clave($input['password'], $error_encontrado, 6, 16, TRUE, TRUE, TRUE))
	{
		$error = $error_encontrado;
		$respuesta['resultado']="ERROR";
    	$respuesta['mensaje']=$error;		
		echo json_encode($respuesta);
		exit();		
	}
	elseif($input['confirmacion']=="")
	{
		$respuesta['resultado']="ERROR";
    	$respuesta['mensaje']="Debe ingresar la confirmación de la password.";		
		echo json_encode($respuesta);
		exit();
	}
	elseif($input['password'] != $input['confirmacion'])
	{
		$respuesta['resultado']="ERROR";
    	$respuesta['mensaje']="La password y la confirmación deben ser iguales.";		
		echo json_encode($respuesta);
		exit();
	}			
	else
	{
	    $sql = "INSERT INTO usuario_padre 
	          (nombre, apellido, mail, password)
	          VALUES
	          (:nombre, :apellido, :mail, :password)";
	    $statement = $dbConn->prepare($sql);
	    $nombre=strtoupper($input['nombre']);
	    $apellido=strtoupper($input['apellido']);
	    $statement->bindParam(':nombre', $nombre);
	    $statement->bindParam(':apellido', $apellido);
	    $statement->bindParam(':mail', $input['mail']);
	    $statement->bindParam(':password', $input['password']);	    
	    $statement->execute();
		session_start();//Inicia la sesión
		$_SESSION['username'] = $input['mail'];	
		$evento = "REGISTRACION DE USUARIO PADRE";	
		
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$fecha_formateada = date("Y-m-d H:i:s",time());		
		//Graba registro en tabla Log
		$sql_padre = $dbConn->prepare("SELECT * FROM usuario_padre where mail=:mail");
		$sql_padre->bindParam(':mail', $input['mail']);	
		$sql_padre->setFetchMode(PDO::FETCH_ASSOC);
		$sql_padre->execute();
		$fila_padre= $sql_padre->fetch();
		$id = $fila_padre['mail'];
	    $sql = "INSERT INTO log 
	          (fecha, evento, usuario)
	          VALUES
	          (:fecha, :evento, :usuario)";
	    $statement = $dbConn->prepare($sql);	   
	    $statement->bindParam(':fecha', $fecha_formateada);
	    $statement->bindParam(':evento', $evento);
	    $statement->bindParam(':usuario', $input['mail']);	        
	    $statement->execute();

		header("HTTP/1.1 200 OK");
	   	$respuesta['resultado']="OK";
    	$respuesta['mensaje']="";
    	$_SESSION['username'] = $fila_padre['nombre'];
	    $respuesta['user'] = $_SESSION['username'];
		echo json_encode($respuesta);
		exit();
	}
}

?>