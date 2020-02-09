<!--<script type="text/javascript" src="./js/FormActualizaHijos.js"></script>-->
<?php 
//Se verifican los errores
$error=$_GET['d'];
$error_texto=$_GET['x'];
if ($error == 1){
	$error = "Debe ingresar su usuario.";
}elseif ($error == 2){
	$error = "Debe ingresar el alias.";
}elseif ($error == 3){
	$error = "Ingrese la edad.";
}elseif ($error == 4){
	$error = "Ingrese la password.";
}elseif ($error == 5){
	$error = $error_texto;
}elseif ($error == 6){
	$error = "Ya existe un usuario.";
}elseif ($error == 7){
	$error = "Datos insertados en forma correcta.";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Actualizar Hijos</title>
	<link rel="stylesheet" href="style/style-site.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Patua+One&display=swap" rel="stylesheet">

</head>
<body>
<header class="pantalla-completa" id="index"> 
<nav class="navbar navbar-expand-lg navbar-light menu-admin-style fixed-top">
      <a class="navbar-brand" href="#">
          <img src="./images/logo.png" width="150px" alt="Arkidia">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#">Opción 1</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Opción 2</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Opción 3</a>
          </li>
        </ul>
      </div>
	</nav>
</header>

<div>
	<h1 class="titulo">Crear Hijos</h1>
	<!-- Verificar.php -->
	<form id="formActualizarHijos" action="php/UpdateHijo.php" method="post" onsubmit="return validarFormHijos(this);">	

		<input type="text" id="usuario" name="usuario" size="10" placeholder="Usuario" maxlength="10"/>
		<input type="text" id="alias" name="alias" size="10" placeholder="Alias" maxlength="10"/>
		<input type="text" id="edad" name="edad" size="6" placeholder="Edad" maxlength="2" />
		<input type="password" id="password" name="password" size="10" placeholder="Contraseña" maxlength="10"/>

		<!--Se muestran los mensajes del lado del servidor-->				
		<br/><?php echo $error;?><br/><br/>
		<input type="submit" value="Agregar" /><br/><br/>				
		

	</form>
	<center><div id="resultado"></div></center>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
