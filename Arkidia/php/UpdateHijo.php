<?php  
	include("conexion.php");
	include("funcionClave.php");
	include("log.php");
	$error_encontrado=""; 
	/////////////////////////////////////////////////////////////////////////////
	$usuario = $conn->real_escape_string($_POST['usuario']);
	$alias = $conn->real_escape_string($_POST['alias']);
	$edad = $conn->real_escape_string($_POST['edad']);
	$password = $conn->real_escape_string($_POST['password']);
	$error = $conn->real_escape_string($_GET[$error_encontrado]);
	session_start();
	$mail = $_SESSION['username'];
	/////////////////////////////////////////////////////////////////////////////

	//Se valida el ingreso de los datos requeridos
	if(!isset($usuario) || empty($usuario))
	{
		//"Ingrese el nombre de usuario" 
		header("location:../actualizarHijos.php?d=1");
	}
	elseif (!isset($alias) || empty($alias)) 
	{
		//"Ingrese el alias."
		header("location:../actualizarHijos.php?d=2");
	}
	elseif (!isset($edad) || empty($edad))
	{
		//"ingrese la edad"
		header("location:../actualizarHijos.php?d=3");
	}
	elseif(!isset($password) || empty($password))
	{
		//"Ingrese la password."
		header("location:../actualizarHijos.php?d=4");
	}
	elseif(!validar_clave($password, $error_encontrado, 6, 16, TRUE, TRUE, TRUE))
	{
		$error = $error_encontrado;
		header("location:../actualizarHijos.php?d=5&x=$error");	
	}else	   
	{
		$b_usuario = mysqli_query($conn, "SELECT usuario FROM usuario_hijo WHERE usuario = '$usuario'");
		$fila = mysqli_fetch_array($b_usuario);

		if ($usuario == $fila['usuario'])
		{
			//"Ya existe un usuario."
			header("location:../actualizarHijos.php?d=6");
		}else
		{
			//Guardo en mayúsculas
			$usuario = strtoupper($usuario);
			$alias = strtoupper($alias);		
			
			
			//Graba registro en tabla usuario_hijos
			mysqli_query($conn, "INSERT INTO usuario_hijo (usuario, alias, usuario_padre, edad, password)
				VALUES ('$usuario', '$alias', '$mail', '$edad', '$password')");
			session_start();//Inicia la sesión
			$_SESSION['username'] = $usuario;			
			$fecha = new DateTime();
			$fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";	
			//Graba registro en el log	
    		mysqli_query($conn, "INSERT INTO log (fecha, evento, usuario)
    			VALUES ('$fecha_formateada', 'AGREGO UN HIJO', '$mail')");			
			//"Datos insertados en forma correcta."."<br/><br/><br/>"
			header("location:../actualizarHijos.php?d=7");				
		}
	}
?>