<?php
function graba_log($evento,$id){	
	$fecha = new DateTime();
	$fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";	
	//Graba registro en el log	
    mysqli_query($conn, "INSERT INTO log (fecha, evento, usuario)
    	VALUES ('$fecha_formateada', '$evento', '$id')");	
}
?>