<?php  
	include("conexion.php");
	include("log.php");
	$error_encontrado=""; 
	/////////////////////////////////////////////////////////////////////////////
	$categoria = $conn->real_escape_string($_POST['categoria']);
	$imagen = $conn->real_escape_string($_POST['imagen']);
	$error = $conn->real_escape_string($_GET[$error_encontrado]);
	session_start();
	$usuario = $_SESSION['username'];
	/////////////////////////////////////////////////////////////////////////////

	//Se valida el ingreso de los datos requeridos
	if(!isset($categoria) || empty($categoria))
	{
		//"Ingrese el nombre de usuario" 
		header("location:../formCategorias.php?d=1");
	}
	elseif (!isset($imagen) || empty($imagen)) 
	{
		//"Ingrese el alias."
		header("location:../formCategorias.php?d=2");
	}
	else	   
	{
		$b_categoria = mysqli_query($conn, "SELECT descripcion FROM categorias WHERE descripcion = '$categoria'");
		$fila = mysqli_fetch_array($b_categoria);

		if ($categoria == $fila['descripcion'])
		{
			//"Ya existe una categoría."
			header("location:../formCategorias.php?d=3");
		}else
		{
			//Guardo en mayúsculas
			$categoria = strtoupper($categoria);
						
			//Graba registro en tabla usuario_hijos
			mysqli_query($conn, "INSERT INTO categorias VALUES (0, '$categoria', '$imagen')");						
			$fecha = new DateTime();
			$fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";	
			//Graba registro en el log	
    		mysqli_query($conn, "INSERT INTO log (fecha, evento, usuario)
    			VALUES ('$fecha_formateada', 'AGREGO CATEGORIA', '$usuario')");			
			//"Datos insertados en forma correcta."."<br/><br/><br/>"
			header("location:../formCategorias.php?d=4");				
		}
	}
?>