<?php
session_start();
if($_SESSION['username']!="") 
{   
    $mail=$_SESSION['username'];
    $fechaGuardada = $_SESSION['ultimoAcceso']; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 

    //comparamos el tiempo transcurrido 
     if($tiempo_transcurrido >= 1200) { 
     //si pasaron 10 minutos o más 
      session_destroy(); // destruyo la sesión 
      header("location: https://concodigo.com/comunidad/login");
      //sino, actualizo la fecha de la sesión 
    }
	
}
else
{
	header("location: https:https://concodigo.com/comunidad/login");
}

?>