<?php
  include "config.php";
  //Abrir conexion a la base de datos
  function connect($db)
  {
      try {
          $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']}", $db['username'], $db['password']);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $conn;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }
 //Obtener parámetros para updates
 function getParams($input)
 {
    $filterParams = [];
    foreach($input as $param => $value)
    {
            $filterParams[] = "$param=:$param";
    }
    return implode(", ", $filterParams);
  }
  //Asociar todos los valores a un sql
  function bindAllValues($statement, $params)
  {
    foreach($params as $param => $value)
    {
        $statement->bindValue(':'.$param, $value);
    }
    return $statement;
   }

   //Asociar todos los parámetros a un sql
     function bindAllParam($statement, $params)
  {
    foreach($params as $param => $value)
    {
        $value= strtoupper($value);
        $statement->bindParam(':'.$param, $value);
    }
    return $statement;
   }

//*******************************//
//VALIDA CLAVE EN LA REGISTRACION//
//*******************************//
function validar_clave($clave,&$error_clave, $long_min, $long_max, $opc_letras, $opc_numeros, $opc_letrasMayus){
  
  $opc_letras = FALSE; //  FALSE para quitar las letras
  $opc_numeros = FALSE; // FALSE para quitar los números
  $opc_letrasMayus = FALSE; // FALSE para quitar las letras mayúsculas
  $opc_especiales = FALSE; // FALSE para quitar los caracteres especiales   
  $long_min = 6;  
  $long_max = 16;
  
   if(strlen($clave) < $long_min){
      $error_clave = "La clave debe tener al menos 6 caracteres.";

      return false;
   }
   if(strlen($clave) > $long_max){
      $error_clave = "La clave no puede tener más de " . $long_max . " caracteres.";
      return false;
   }
   if ($opc_letras == TRUE && !preg_match('`[a-z]`',$clave)){
      $error_clave = "La clave debe tener al menos una letra minúscula.";
      return false;
   }
   if ($opc_letrasMayus == TRUE && !preg_match('`[A-Z]`',$clave)){
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

function comprobar_email($email){
   $mail_correcto = 0;
   //compruebo unas cosas primeras
   if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
      if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
         //miro si tiene caracter .
         if (substr_count($email,".")>= 1){
            //obtengo la terminacion del dominio
            $term_dom = substr(strrchr ($email, '.'),1);
            //compruebo que la terminación del dominio sea correcta
            if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
               //compruebo que lo de antes del dominio sea correcto
               $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
               $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
               if ($caracter_ult != "@" && $caracter_ult != "."){
                  $mail_correcto = 1;
               }
            }
         }
      }
   }
   
   if ($mail_correcto)
      return 1;
   else
      return 0;
}

function graba_log($evento,$id){ 

  $fecha = new DateTime();
  $fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n"; 
  //Graba registro en el log
  $sql_log = "INSERT INTO log 
            (fecha, evento, usuario)
            VALUES
            (:fecha, :evento, :usuario)";
  $state = $dbConn->prepare($sql_log);
  $state->bindValue(':fecha', $fecha);
  $state->bindValue(':evento', $evento);
  $state->bindValue(':usuario', $id);       
  $state->execute();
}
 ?>
