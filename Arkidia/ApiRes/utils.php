<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
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

  function obtenerHijos(){
   
    $query = connect($db)->query('SELECT * FROM usuario_hijo');
    return $query;
  }

  function registroLog($db, $evento, $usuario){
    $dbConn =  connect($db); 
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha_formateada = date("Y-m-d H:i:s",time());    
    //Graba registro en tabla Log
    try{
      $sql = "INSERT INTO log 
            (fecha, evento, usuario)
            VALUES
            (:fecha, :evento, :usuario)";
      $statement = $dbConn->prepare($sql);  
      $statement->bindParam(':fecha', $fecha_formateada);
      $statement->bindParam(':evento', $evento);
      $statement->bindParam(':usuario', $usuario);          
      $statement->execute();
    }catch(Exception $e)
    {
      $e->getMessage();          
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="log ".$e;
      echo json_encode(  $respuesta  );
      exit();
    }         
  }

  function cursoCompleto($db, $id_curso, $usuario){
    $dbConn =  connect($db); 
    $sql = $dbConn->prepare("SELECT * FROM contenido_alumno 
                              WHERE id_curso = :id_curso AND usuario = :usuario");
    $sql->bindValue(':id_curso', $id_curso);
    $sql->bindValue(':usuario', $usuario);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $fila_contenido = $sql->fetchAll();   

    foreach($fila_contenido as $row)
    {
        if($row['porcentaje_avance'] == 100 || $row['porcentaje_avance'] == 99)
        {
          $contenido_finalizado = true;
        }else
        {
          $contenido_finalizado = false;
          break;
        }        
    }
    $sql = $dbConn->prepare("SELECT * FROM challenge_alumno 
                                                WHERE id_curso = :id_curso AND usuario = :usuario");
    $sql->bindValue(':id_curso', $id_curso);
    $sql->bindValue(':usuario', $usuario);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $fila_challenge = $sql->fetchAll();
   
    foreach($fila_challenge as $row)
    {
        if($row['ind_completo'] == 1)
        {
          $challenge_finalizado = true;
        }else
        {
          $challenge_finalizado = false;
          break;
        }        
    }
    if($contenido_finalizado == true && $challenge_finalizado == true)
    {
      $ind_completo = 1;
      $data=[
        'id_curso'    => $id_curso,
        'usuario'     => $usuario,
        'ind_completo'=> $ind_completo    
      ];
      $sql = "UPDATE curso_alumno SET ind_completo = :ind_completo 
                                      WHERE id_curso = :id_curso AND usuario = :usuario";
      $statement = $dbConn->prepare($sql);     
      $statement->execute($data);      
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
  //Generación aleatoria 
  function claveAleatoria(){
  //$longitud, $opcLetra, $opcNumero, $opcMayus, $opcEspecial, $longitud_min, $longitud_max){  
    $opc_letras = FALSE; //  FALSE para quitar las letras
    $opc_numeros = TRUE; // FALSE para quitar los números
    $opc_letrasMayus = FALSE; // FALSE para quitar las letras mayúsculas
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
    $password=array();
    for( $i=0; $i<$longitud; $i++) {
      str_shuffle($listado);
      $password[$i] = $listado[rand(0,strlen($listado))];      
    }
    //devuelve un array con la clave nueva
    return $password;
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

function calculaEdad($nacimiento){
  $cumpleanos = new DateTime($nacimiento);
  $hoy = new DateTime();
  $annos = $hoy->diff($cumpleanos);
  return $annos->y; 
}

function enviaCorreo($destinatario, $nombre){


    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
    // Es el correo definido en SES.
    $sender = 'polrojas@gmail.com';
    $senderName = 'polrojas@gmail.com';

    
    // Es correo del destinatario
    $recipient = $destinatario;

    // SES SMTP user name.
    $usernameSmtp = 'AKIAVBAXSKP7CR4RICAV';

    // SES SMTP password.
    $passwordSmtp = 'BGiptBA8WSpVFay5ttRhMfPteyB9nIDKQOIhHlXdolXo';

    // Cofiguración del correo.
    $configurationSet = 'ConfigSet';

    // Region del servidor SES
    $host = 'email-smtp.us-east-1.amazonaws.com';
    $port = 587;

    // Asunto
    $subject = 'Recupero de clave de usuario';

    // The plain-text body of the email
    $bodyText =  "Casi que no lo puedo creer.";

    // El formato HTML body del email


    $bodyHtml = 
        "
        <h3>Hola, <b> $nombre </b>.</h3><br>
            
        Podrás registrar tu nueva contraseña a la ruta que te indicamos a continuación:
        <a href=http://ec2-35-173-152-223.compute-1.amazonaws.com/formCambioClave.html>
        https://arkidia.com.ar/formCambioClave.html
        </a><br>
        Una vez dentro, podrás modificar esta contraseña que te hemos asignado por otra que prefieras     para tu usuario.
        <br><br>
        Atentamente, El equipo de Arkidia.<br>
        <a href=http://ec2-35-173-152-223.compute-1.amazonaws.com>
        https://arkidi.com.ar
        </a><br>
        <img src=http://ec2-35-173-152-223.compute-1.amazonaws.com/images/logo.png alt=Logo Arkidia
        width=100 height=auto>    
        ";

    $mail = new PHPMailer(); 

    try {
        // Configuración del SMTP.
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->setFrom($sender, $senderName);
        $mail->Username   = $usernameSmtp;
        $mail->Password   = $passwordSmtp;
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'tls';
        //$mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

        // Destinatario del correo.
        $mail->addAddress($recipient);
        // You can also add CC, BCC, and additional To recipients here.

        // Especificación del mensaje.
        $mail->isHTML(true);
        $mail->charset    = "UTF-8";
        $mail->Subject    = $subject;
        $mail->Body       = $bodyHtml;      
        $mail->AltBody    = $bodyText;        
        $mail->Send();


    } catch (phpmailerException $e) {
        echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
    } catch (Exception $e) {
        echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
    }

}

function enviaClave($destinatario, $nombre, $clave){


    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
    // Es el correo definido en SES.
    $sender = 'polrojas@gmail.com';
    $senderName = 'polrojas@gmail.com';

    
    // Es correo del destinatario
    $recipient = $destinatario;

    // SES SMTP user name.
    $usernameSmtp = 'AKIAVBAXSKP7CR4RICAV';

    // SES SMTP password.
    $passwordSmtp = 'BGiptBA8WSpVFay5ttRhMfPteyB9nIDKQOIhHlXdolXo';

    // Cofiguración del correo.
    $configurationSet = 'ConfigSet';

    // Region del servidor SES
    $host = 'email-smtp.us-east-1.amazonaws.com';
    $port = 587;

    // Asunto
    $subject = 'Recupero de clave de usuario';

    // The plain-text body of the email
    $bodyText =  "Casi que no lo puedo creer.";

    // El formato HTML body del email


    $bodyHtml = 
        "
        <h3>Hola, <b> $nombre </b>.</h3><br>
            
        Para confirmar la modificación solicitada, tenés que ingresar el código que se indica a continuación en el formulario antes que sea inhabilitada a las 24 hs.<br><br>

        <div align = center; border= 1px solid #369; padding = 5px;>
          <h3>$clave</h3>
        </div> <br><br>
        Atentamente, El equipo de Arkidia.<br>
        <a href=http://ec2-35-173-152-223.compute-1.amazonaws.com>
        https://arkidi.com.ar
        </a><br>
        <img src=http://ec2-35-173-152-223.compute-1.amazonaws.com/images/logo.png alt=Logo Arkidia
        width=100 height=auto>    
        ";

    $mail = new PHPMailer(); 

    try {
        // Configuración del SMTP.
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->setFrom($sender, $senderName);
        $mail->Username   = $usernameSmtp;
        $mail->Password   = $passwordSmtp;
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'tls';
        //$mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

        // Destinatario del correo.
        $mail->addAddress($recipient);
        // You can also add CC, BCC, and additional To recipients here.

        // Especificación del mensaje.
        $mail->isHTML(true);
        $mail->charset    = "UTF-8";
        $mail->Subject    = $subject;
        $mail->Body       = $bodyHtml;      
        $mail->AltBody    = $bodyText;        
        $mail->Send();


    } catch (phpmailerException $e) {
        echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
    } catch (Exception $e) {
        echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
    }

}
?>
