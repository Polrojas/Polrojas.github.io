<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
//Muestra el contenido de la tabla hijos
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['usuario']))
    {
      //Mostrar un hijo en particular
      $sql = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");
      $sql->bindValue(':usuario', $_GET['usuario']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
    }
    elseif (isset($_GET['usuario_padre'])) 
    {
      //Mostrar lista de hijos de un padre
      $sql = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario_padre=:usuario_padre");
      $sql->bindValue(':usuario_padre', $_GET['usuario_padre']);
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
    }
    else
    {
      $sql = $dbConn->prepare("SELECT * FROM usuario_hijo");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
    }
/*    $evento = "CONSULTA DEL HIJO: " . $_GET['usuario'];    
    $fecha = new DateTime();
    $fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";   
    //Graba registro en tabla Log
    $sql = "INSERT INTO log 
          (fecha, evento, usuario)
          VALUES
          (:fecha, :evento, :usuario)";
    $statement = $dbConn->prepare($sql);     
    $statement->bindParam(':fecha', $fecha_formateada);
    $statement->bindParam(':evento', $evento);
    $statement->bindParam(':usuario', $fila_hijo['usuario_padre']);          
    $statement->execute();     */
}
////////////////////////////////////////////////
//Crea una relación nueva en la tabla de hijos//
///////////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    //Se busca si existe el usuario
    $sql_hijo = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");
    $sql_hijo->bindParam(':usuario', $input['usuario']); 
    $sql_hijo->setFetchMode(PDO::FETCH_ASSOC);
    $sql_hijo->execute();
    $fila_hijo= $sql_hijo->fetch();

    if(isset($fila_hijo['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="El usuario ya existe en la tabla.";    
      echo json_encode($respuesta);
      exit();
    }
    elseif($input['usuario'] == "")
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe ingresar el usuario.";    
      echo json_encode($respuesta);
      exit();
    }
    elseif($input['alias'] == "")
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe ingresar el alias.";    
      echo json_encode($respuesta);
      exit();
    }
    elseif($input['usuario_padre'] == "")
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe ingresar la dirección de correo del padre.";    
      echo json_encode($respuesta);
      exit();      
    }
    elseif($input['fecha'] == "")
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe ingresar la fecha de nacimiento de su hijo.";    
      echo json_encode($respuesta);
      exit();        
    }
    elseif($input['password']=="")
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe ingresar la password de su hijo.";    
      echo json_encode($respuesta);
      exit();        
    }
    elseif(!validar_clave($input['password'], $error_encontrado, 6, 10, FALSE, FALSE, FALSE))
    {
      $error = $error_encontrado;
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']=$error;   
      echo json_encode($respuesta);
      exit();   
    }    
    elseif($input['avatar']=="")
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe seleccionar el avatar de su hijo.";    
      echo json_encode($respuesta);
      exit();        
    }    
    else
    { 

      $sql = "INSERT INTO usuario_hijo
            (usuario, alias, usuario_padre, fecha_nacimiento, password, avatar)
            VALUES
            (:usuario, :alias, :usuario_padre, :fecha_nacimiento, :password, :avatar)";
      $statement = $dbConn->prepare($sql);      
      $statement->bindParam(':usuario', $input['usuario']);
      $statement->bindParam(':alias', $input['alias']);
      $statement->bindParam(':usuario_padre', $input['usuario_padre']);
      $statement->bindParam(':fecha_nacimiento', $input['fecha']);
      $statement->bindParam(':password', $input['password']);
      $statement->bindParam(':avatar', $input['avatar']); 
      $statement->execute();
      //graba_log("SE REGISTRO EN EL SISTEMA",$usuario)
      $evento = "ALTA DEL HIJO ".$input['usuario'];    
      $fecha = new DateTime();
      $fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";   
      //Graba registro en tabla Log
      $sql = "INSERT INTO log 
            (fecha, evento, usuario)
            VALUES
            (:fecha, :evento, :usuario)";
      $statement = $dbConn->prepare($sql);     
      $statement->bindParam(':fecha', $fecha_formateada);
      $statement->bindParam(':evento', $evento);
      $statement->bindParam(':usuario', $input['usuario_padre']);          
      $statement->execute();      
      header("HTTP/1.1 200 OK");
      $respuesta['resultado']="OK";
      $respuesta['mensaje']="";    
      echo json_encode($respuesta);
      exit();
    }
}
//Elimina un hijo de la tabla enviando el usuario
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
  //Busco al usuario para obtener el mail del padre
  $usuario = $_GET['usuario'];
  $sql_hijo = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");
  $sql_hijo->bindParam(':usuario', $usuario); 
  $sql_hijo->setFetchMode(PDO::FETCH_ASSOC);
  $sql_hijo->execute();
  $fila_hijo= $sql_hijo->fetch();  


  $statement = $dbConn->prepare("DELETE FROM usuario_hijo where usuario=:usuario");
  $statement->bindValue(':usuario', $usuario);
  $statement->execute();
  $evento = "ELIMINO AL HIJO ".$usuario;    
  $fecha = new DateTime();
  $fecha_formateada = $fecha->format('Y-m-d H:i:s') . "\n";   
  //Graba registro en tabla Log
  $sql = "INSERT INTO log 
        (fecha, evento, usuario)
        VALUES
        (:fecha, :evento, :usuario)";
  $statement = $dbConn->prepare($sql);     
  $statement->bindParam(':fecha', $fecha_formateada);
  $statement->bindParam(':evento', $evento);
  $statement->bindParam(':usuario', $fila_hijo['usuario_padre']);          
  $statement->execute();  
  header("HTTP/1.1 200 OK");
  exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $usuario = $input['usuario'];
    //Se busca si existe el usuario
    $sql_hijo = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");
    $sql_hijo->bindParam(':usuario', $input['usuario']); 
    $sql_hijo->setFetchMode(PDO::FETCH_ASSOC);
    $sql_hijo->execute();
    $fila_hijo= $sql_hijo->fetch();

    $fields = getParams($input);
    $sql = "
          UPDATE usuario_hijo SET $fields WHERE usuario='$usuario'";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();

    $evento = "MODIFICO LOS DATOS DEL HIJO: " . $usuario;    
    $fecha = new DateTime();
    $fecha_formateada = $fecha->format('Y-m-d H:i:s');   
    //Graba registro en tabla Log
    $sql = "INSERT INTO log 
          (fecha, evento, usuario)
          VALUES
          (:fecha, :evento, :usuario)";
    $statement = $dbConn->prepare($sql);     
    $statement->bindParam(':fecha', $fecha_formateada);
    $statement->bindParam(':evento', $evento);
    $statement->bindParam(':usuario', $fila_hijo['usuario_padre']);          
    $statement->execute();

    header("HTTP/1.1 200 OK");
    $respuesta['resultado']="OK";
    $respuesta['mensaje']="";    
    echo json_encode($respuesta);
    exit();
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
