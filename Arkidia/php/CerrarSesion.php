<?php
include("conexion.php");
include("log.php");
session_start();
$sel = mysqli_query($conn, "SELECT * FROM log WHERE usuario = '$_SESSION[username]'");
$fila = mysqli_fetch_array($sel);
$fecha = new DateTime();
$fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";		
graba_log("CERRO LA SESION",$_SESSION[username]){
session_destroy();
echo "Ha cerrado sesión.";
?>