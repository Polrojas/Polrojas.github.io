<?php
require_once "config.php";
require_once "utils.php";
require_once "SegCla.php";
//include "SegCla.php";
$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $input = $_POST;
  /////////////////////////////////////
  //Buscamos en la tabla usuario_hijo//
  /////////////////////////////////////
  
  if(!isset($input['usuario']))
  {
    $respuesta['resultado'] = "ERROR";
    $respuesta['mensaje'] = "Debe enviar el usuario por POST";
    echo json_encode($respuesta);
    exit();
  }
  if(!isset($input['password']))
  {
    $respuesta['resultado'] = "ERROR";
    $respuesta['mensaje'] = "Debe enviar la password por POST";
    echo json_encode($respuesta);
    exit();
  }
 
  $sql_hijo = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");
  $sql_hijo->bindParam(':usuario',$input['usuario']);  
  $sql_hijo->setFetchMode(PDO::FETCH_ASSOC);
  $sql_hijo->execute();
  $fila_hijo= $sql_hijo->fetch();

  //////////////////////////////////////
  //Buscamos en la tabla usuario_padre//
  //////////////////////////////////////

  $sql_padre = $dbConn->prepare("SELECT * FROM usuario_padre where mail=:mail");
  $sql_padre->bindParam(':mail', $input['usuario']);  
  $sql_padre->setFetchMode(PDO::FETCH_ASSOC);
  $sql_padre->execute();
  $fila_padre= $sql_padre->fetch();

  //////////////////////////////////////////////
  //Buscamos en la tabla usuario_administrador//
  //////////////////////////////////////////////

  $sql_administrador = $dbConn->prepare("SELECT * FROM usuario_administrador where mail=:mail");
  $sql_administrador->bindParam(':mail', $input['usuario']);
  $sql_administrador->setFetchMode(PDO::FETCH_ASSOC);  
  $sql_administrador->execute();
  $fila_administrador = $sql_administrador->fetch();
  $passwordE = encriptar($input['password']);
  if (!empty($fila_hijo['usuario']))
  {
    if($passwordE == $fila_hijo['password'])
    {
      header("HTTP/1.1 200 OK");
      session_start();//Inicia la sesión
      $_SESSION['username'] = $fila_hijo['alias'];
      $respuesta['resultado'] = "OK";
      $respuesta['mensaje'] = ""; 
      $respuesta['page'] = "HIJO";
      $respuesta['user'] = $_SESSION['username'];
      echo json_encode($respuesta);
    }else{
      $respuesta['resultado'] = "ERROR";
      $respuesta['mensaje'] = "Uno de los datos es incorrecto.";         
      echo json_encode($respuesta);
      exit();      
    }    
  }elseif(isset($fila_padre['nombre']))
  {
    if($passwordE == $fila_padre['password'])
    {
      header("HTTP/1.1 200 OK");
      session_start();//Inicia la sesión
      $_SESSION['username'] = $fila_padre['nombre'];
      $respuesta['resultado'] = "OK";
      $respuesta['mensaje'] = "";  
      $respuesta['page'] = "PADRE";
      $respuesta['user'] = $_SESSION['username']; 
      echo json_encode($respuesta);
    }else{
      $respuesta['resultado'] = "ERROR";
      $respuesta['mensaje'] = "Uno de los datos es incorrecto.";    
      echo json_encode($respuesta);
      exit();      
    }
  }elseif(isset($fila_administrador['nombre']))
  {
    if($passwordE == $fila_administrador['password'])
    {    
      header("HTTP/1.1 200 OK");
      session_start();//Inicia la sesión
      $_SESSION['username'] = $fila_administrador['nombre'];
      $respuesta['resultado'] = "OK";
      $respuesta['mensaje'] = ""; 
      $respuesta['page'] = "ADMINISTRADOR";
      $respuesta['user'] = $_SESSION['username'];
      echo json_encode($respuesta);    
    }else{
      $respuesta['resultado'] = "ERROR";
      $respuesta['mensaje'] = "Uno de los datos es incorrecto.";    
      echo json_encode($respuesta);
      exit();      
    }      
  }else
  {
    $respuesta['resultado'] = "ERROR";
    $respuesta['mensaje'] = "No es un usuario registrado.";    
    echo json_encode($respuesta);
    exit();
  }
  $evento = "INGRESO AL SISTEMA: ";    
  date_default_timezone_set('America/Argentina/Buenos_Aires');
  $fecha_formateada = date("Y-m-d H:i:s",time());    
  //Graba registro en tabla Log
  $sql = "INSERT INTO log 
        (fecha, evento, usuario)
        VALUES
        (:fecha, :evento, :usuario)";
  $statement = $dbConn->prepare($sql);     
  $statement->bindParam(':fecha', $fecha_formateada);
  $statement->bindParam(':evento', $evento);
  $statement->bindParam(':usuario', $_SESSION['username']);          
  $statement->execute();
  exit();
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
