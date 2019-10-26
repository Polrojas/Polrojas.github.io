<?php 
$error=$_GET['d'];
$texto=$_GET['x'];
$puntos=$_GET['z'];
if ($error == 1)
	{
		$error = "Debe ingresar la dirección de mail.";
	}
elseif ($error == 2) 
	{
		$error = "Debe ingresar la Contraseña.";
	}
elseif($error == 3)
	{
		$error = "Los datos ingresados no corresponden a un usuario registrado. ";
	}
elseif($error == 4)
	{
		$error = "Datos modificados en forma correcta.";
	}
elseif($error == 5)
	{
		$error = "Debe ingresar la dirección de mail.";
	}
elseif ($error == 6) 
	{
		$error = "Debe ingresar la contraseña actual.";
	}
elseif ($error == 7)
	{
		$error = "La contraseña actual ingresada es incorrecta.";
	}
elseif ($error == 8)
	{
		$error = "Debe ingresar la contraseña nueva.";
	}
elseif ($error == 9)
	{
		$error = $error = "La contraseña debe tener al menos 6 y como máximo 16 caracteres alfanuméricos, incluyendo mayúsculas."."<br/>".$texto;
	}
elseif($error == 10)
	{
		$error = "Debe ingresar la confirmación contraseña nueva.";
	}
elseif($error == 11)
	{
		$error = "La password nueva y la confirmación deben ser iguales.";
	}
elseif($error == 12)
	{
		$error = "Se modificó en forma correcta la contraseña.";
	}
elseif($error == 13)
	{
		$error = "Debe ingresar su nombre.";
	}
elseif($error == 14)
	{
		$error = "Debe ingresar la razón social de la empresa.";
	}
elseif($error == 15)
	{
		$error = "Hubo un error en el envío del correo.";	
	}
elseif($error == 16)
	{
		$error = "El correo se ha enviado en forma correcta";
	}
elseif($error == 17)
	{
		$error = "Ingrese su consulta en el cuadro de texto";
	}
elseif($error == 18)
	{
		$error= "Participó en el juego con anterioridad";
	}
elseif($error == 19)
	{
		$error = "Debe responder todos los ítems";
	}
elseif($error == 20)
	{
		$error = "Ha obtenido " . $puntos . " puntos adicionales.";
	}
elseif($error == 21)
	{
		$error = "Ya has participados de este juego y obtuviste " . $puntos . " puntos adicionales.";
	}
elseif($error==22)
	{
		$error = "Debe ingresar el titulo.";
	}
elseif($error==23)
	{
		$error = "Debe ingresar la categoría.";
	}
elseif($error==24)
	{
		$error = "Debe ingresar el detalle funcional";
	}
elseif($error==25)
	{
		$error = "Debe ingresar el estado del item";
	}
?>