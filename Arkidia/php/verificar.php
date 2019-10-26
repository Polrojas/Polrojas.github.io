<?php  
//Se incluye el programa en el cual se especifica los datos para la conexión con la base de datos
include("conexion.php");
include("log.php");
//Se validan los datos y se devuelve un valor a formLoguin.php para que muestre mensaje

/////////////////////////////////////////////////////////////////////////////
$usuario = $conn->real_escape_string($_POST['login']);
$password = $conn->real_escape_string($_POST['password']);
/////////////////////////////////////////////////////////////////////////////


if(!isset($usuario) || empty($usuario))
{	
	//"Debe ingresar su usuario."
	header("location:../login.php?d=1");	
}elseif (!isset($password) || empty($password))
{
	//"Debe ingresar la password."
	header("location:../login.php?d=2");	
}else{
	////////////////////////////////////////////////////////////
	//Se busca el usuario / password en la tabla usuario_padre//
	///////////////////////////////////////////////////////////
	$sel_padre = mysqli_query($conn, "SELECT * FROM usuario_padre WHERE mail = '$usuario'");	
	$sesion_padre = mysqli_fetch_array($sel_padre);
	////////////////////////////////////////////////////////////////////
	//Se busca el usuario / password en la tabla usuario_administrador//
	///////////////////////////////////////////////////////////////////
	$sel_administrador = mysqli_query($conn, "SELECT * FROM usuario_administrador WHERE mail = '$usuario'");	
	$sesion_administrador = mysqli_fetch_array($sel_administrador);
	////////////////////////////////////////////////////////////
	//Se busca el usuario / password en la tabla usuario_hijo//
	///////////////////////////////////////////////////////////
	$sel_hijo = mysqli_query($conn, "SELECT * FROM usuario_hijo WHERE usuario = '$usuario'");	
	$sesion_hijo = mysqli_fetch_array($sel_hijo);
	//Si el array no está vacío y la password ingresada coincide con la tabla

	if(!empty($sesion_padre) && ($_POST['password'] == $sesion_padre['password'])){	
		session_start();//Inicia la sesión
		$_SESSION['username'] = $usuario;
		$id = $sesion_padre['mail'];
		$fecha = new DateTime();
		$fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";	
		//Graba registro en el log	
		mysqli_query($conn, "INSERT INTO log (fecha, evento, usuario)
			VALUES ('$fecha_formateada', 'INGRESO AL DASHBOARD PADRES', '$id')");				
		//Tiene que entrar en la página de vista del padre
		header("location:../SitioPadre.php");
	}elseif(!empty($sesion_administrador) && ($_POST['password'] == $sesion_administrador['password'])){
		session_start();//Inicia la sesión
		$_SESSION['username'] = $usuario;
		$id = $sesion_administrador['mail'];
		$fecha = new DateTime();
		$fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";	
		//Graba registro en el log	
		mysqli_query($conn, "INSERT INTO log (fecha, evento, usuario)
			VALUES ('$fecha_formateada', 'INGRESO AL DASHBOARD ADMINISTRADOR', '$id')");
		//Entra a la página de carga del administrador
		header("location: ../ArkidiaAdministracion.php");			
	}elseif(!empty($sesion_hijo) && ($_POST['password'] == $sesion_hijo['password'])){
		session_start();//Inicia la sesión
		$_SESSION['username'] = $usuario;
		$id = $sesion_hijo['mail'];
		$fecha = new DateTime();
		$fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";	
		//Graba registro en el log	
		mysqli_query($conn, "INSERT INTO log (fecha, evento, usuario)
			VALUES ('$fecha_formateada', 'INGRESO AL DASHBOARD ARKIDIAN', '$id')");		
		//Tiene que entrar en la página de vista del hijo
		header("location:../SitioHijo.php");
	}else{
		//"Los datos ingresados no corresponden a un usuario registrado. "
		header("location:../login.php?d=3");
	}
}

?>
