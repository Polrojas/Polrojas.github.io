<?php
//********************************************//
//FUNCION PARA  GENERAR CLAVE PARA ENVIAR MAIL//
//********************************************//
	function claveAleatoria(){
		//claveAleatoria($longitud, $opcLetra, $opcNumero, $opcMayus, $opcEspecial, $longitud_min, $longitud_max){	
		$opc_letras = TRUE; //  FALSE para quitar las letras
		$opc_numeros = TRUE; // FALSE para quitar los números
		$opc_letrasMayus = TRUE; // FALSE para quitar las letras mayúsculas
		$opc_especiales = FALSE; // FALSE para quitar los caracteres especiales		
		$longitud = 8;		
				 
		$letras ="abcdefghijklmnopqrstuvwxyz";
		$numeros = "1234567890";
		$letrasMayus = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$especiales ="|@#~$%()=^*+[]{}-_";
		$listado = "";
			 
		if ($opc_letras == TRUE) {$listado .= $letras; }
		if ($opc_numeros == TRUE) {$listado .= $numeros; }
		if($opc_letrasMayus == TRUE) {$listado .= $letrasMayus; }
		if($opc_especiales == TRUE) {$listado .= $especiales; }
		 
		str_shuffle($listado); //baraja una cadena. Es creada una permutación de todas las posibles.
		for( $i=1; $i<=$longitud; $i++) {
			$password[$i] = $listado[rand(0,strlen($listado))];
			str_shuffle($listado);
		}
		//devuelve un array con la clave nueva
		return $password;
	}

//*******************************//
//VALIDA CLAVE EN LA REGISTRACION//
//*******************************//
function validar_clave($clave,&$error_clave, $long_min, $long_max, $opc_letras, $opc_numeros, $opc_letrasMayus){
	/*
	$opc_letras = TRUE; //  FALSE para quitar las letras
	$opc_numeros = FALSE; // FALSE para quitar los números
	$opc_letrasMayus = TRUE; // FALSE para quitar las letras mayúsculas
	$opc_especiales = FALSE; // FALSE para quitar los caracteres especiales		
	$long_min = 6;	
	$long_max = 16;
	*/
   if(strlen($clave) < $long_min){
      $error_clave = "La clave debe tener al menos 6 caracteres.";

      return false;
   }
   if(strlen($clave) > $long_max){
      $error_clave = "La clave no puede tener más de 16 caracteres.";
      return false;
   }
   if ($opc_letras == TRUE && !preg_match('`[a-z]`',$clave)){
      $error_clave = "La clave debe tener al menos una letra minúscula.";
      return false;
   }
   if (!preg_match('`[A-Z]`',$clave)){
      $error_clave = "La clave debe tener al menos una letra mayúscula.";
      return false;
   }
   if ($opc_numeros == TRUE && !preg_match('`[0-9]`',$clave)){
      $error_clave = "La clave debe tener al menos un caracter numérico.";
      return false;
   }
   /*if ($opc_especiales == TRUE && !preg_match('|@#~$%()=^*+[]{}-',$clave)){
      $error_clave = "La clave debe tener al menos un caracter especial.";
      return false;   	
   }*/
   $error_clave = "";
   return true;
}

?>