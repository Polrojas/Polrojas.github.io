<?php  
	include("conexion.php");
	include("funcionClave.php");
	include("funcionEmail.php");	
	$error_encontrado=""; 
	/////////////////////////////////////////////////////////////////////////////
	$nombre = $conn->real_escape_string($_POST['nombre']);
	$apellido = $conn->real_escape_string($_POST['apellido']);
	$mail = $conn->real_escape_string($_POST['correo']);
	$pw = $conn->real_escape_string($_POST['password']);
	$pw2 = $conn->real_escape_string($_POST['password2']);
	$error = $conn->real_escape_string($_GET[$error_encontrado]);
	/////////////////////////////////////////////////////////////////////////////

	//Se valida el ingreso de los datos requeridos
	if(!isset($nombre) || empty($nombre))
	{
		//"Ingrese el nombre de usuario" 
		header("location:../register.php?e=2");
	}
	elseif (!isset($apellido) || empty($apellido)) 
	{
		//"Ingrese el usuario."
		header("location:../register.php??e=3");
	}
	/////////////Validación de email ///////////////
	elseif(!isset($mail) || empty($mail))
	{
		//"Ingrese su dirección de correo."
		header("location:../register.php?e=6");
	}
	elseif(comprobar_email($mail) == 0)
	{
		//"El email ingresado es inválido"
		header("location:../register.php?e=15");
	}
	elseif(!isset($pw) || empty($pw))
	{
		//"Ingrese la password."
		header("location:../register.php?e=4");
	}
	elseif(!isset($pw2) || empty($pw2))
	{
		//"Confirme su password."
		header("location:../register.php?e=5");
	}
	elseif(!validar_clave($pw, $error_encontrado, 6, 16, TRUE, TRUE, TRUE))
	{
		$error = $error_encontrado;
		header("location:../register.php?e=14&x=$error");	
	}else	   
	{
		//Si todos los datos han sido ingresados, se pregunta si la password y confirmación son iguales
		if($pw	== $pw2)
		{

			$b_mail = mysqli_query($conn, "SELECT mail FROM usuario_padre WHERE mail = '$mail'");
			$fila = mysqli_fetch_array($b_mail);

			if ($mail == $fila['mail'])
			{
				//"Ya existe un usuario registrado con esa dirección de mail."
				header("location:../register.php?e=8");
			}else
			{
				//Guardo en mayúsculas
				$nombre = strtoupper($nombre);
				$apellido = strtoupper($apellido);		
				
				//Graba registro en tabla Registro
				mysqli_query($conn, "INSERT INTO usuario_padre (nombre, apellido, mail, password)
					VALUES ('$nombre', '$apellido', '$mail', '$pw')");
				session_start();//Inicia la sesión
				$_SESSION['username'] = $mail;
				$fecha = new DateTime();
				$fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";
				//Graba registro en tabla Log
				$b_mail = mysqli_query($conn, "SELECT mail FROM usuario_padre WHERE mail = '$mail'");
				$fila = mysqli_fetch_array($b_mail);
				$id = $fila['mail'];
				mysqli_query($conn, "INSERT INTO log (fecha, evento, usuario) 
					VALUES ('$fecha_formateada', 'REGISTRACION DE USUARIO', '$id')");				
				//"Datos insertados en forma correcta."."<br/><br/><br/>"
				header("location:../SitioPadre.php");
				//echo "<br/><a href=panelConCodigo.php>Accede a la página de 'ConCódigo'</a><br/><br/>";
			}

		}else
		{
		//"La password y la confirmación deben ser iguales"	
		header("location:../register.php?e=10");
		}

	}
?>