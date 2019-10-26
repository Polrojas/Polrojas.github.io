<?php  
include("conexion.php");
include("funcionClave.php");
if(isset($_POST['email']) && !empty($_POST['email']))   
{	
	$destNombre = "Arkidia<geeknaco@gmail.com>";
	$desde = "From: $destNombre\r\n"; //mail de origen con caracteres especiales  
	$desde .= 'MIME-Version: 1.0' . "\r\n";// version ayuda al servidor 
	$desde .= 'Content-type: text/html; charset=utf-8' . "\r\n"; //modifica contenido y muestra acentos y ñ
	$asunto = "Recuperación de clave de Arkidia";	
	$sel = mysqli_query($conn, "SELECT * FROM usuario_padre 
		   WHERE mail = '$_POST[email]'");
	$fila = mysqli_fetch_array($sel);

	If (!empty($fila)){
		$nombre = $fila['nombre'];
		
		//Llama a rutina que genera clave y convierte el array en un string		
		$pw = implode(claveAleatoria()); 
		$destino = $_POST['email'];
		// Se graba la password aleatoria
		mysqli_query($conn, "UPDATE usuario_padre set password = '$pw' WHERE mail = '$destino'");

		//Se define el cuerpo del mensaje
		$mensaje = "
		Hola, <b>$nombre </b>.<br>
        Ya podés ingresar a <a href=http://ec2-35-173-152-223.compute-1.amazonaws.com/login.php>
        Login de Arkidia</a> y modificá tu clave de acceso a la Comunidad Educativa: <b> $pw </b> 				
		<br><br>
		Una vez dentro, podrás modificar esta contraseña que te hemos asignado por otra que prefieras 		para tu usuario.
		<br><br>
		Atentamente, El equipo de Aekidia.<br><br>
		<a href=http:http://ec2-35-173-152-223.compute-1.amazonaws.com><img src=http://ec2-35-173-152-223.compute-1.amazonaws.com/images/logo.png alt=CON CODIGO></a>		
		";

		
		if(mail($destino, $asunto, $mensaje, $desde))
		{
			$fecha = new DateTime();
			$fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";
			$id = $fila['mail'];
			mysqli_query($conn, "INSERT INTO log (fecha, evento, usuario) VALUES ('$fecha_formateada', 
				'ENVIO DE CLAVE', '$id')");
			header("location:../correo.php?d=1");

		}else
		{
			header("location:../correo.php?d=2");	
		}
	}else
	{
		header("location:../correo.php?d=4");
	}
	
}else
{
	header("location:../correo.php?d=3");
}
?>