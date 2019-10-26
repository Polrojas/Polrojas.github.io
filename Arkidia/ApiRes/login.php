<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $input = $_POST;
  /////////////////////////////////////
  //Buscamos en la tabla usuario_hijo//
  /////////////////////////////////////

  $sql_hijo = $dbConn->prepare("SELECT alias FROM usuario_hijo where usuario=:usuario and password=:password");
  $sql_hijo->bindParam(':usuario', $input['usuario']);
  $sql_hijo->bindParam(':password',$input['password']);
  $sql_hijo->setFetchMode(PDO::FETCH_ASSOC);
  $sql_hijo->execute();
  $fila_hijo= $sql_hijo->fetch();

  //////////////////////////////////////
  //Buscamos en la tabla usuario_padre//
  //////////////////////////////////////

  $sql_padre = $dbConn->prepare("SELECT nombre FROM usuario_padre where mail=:mail and password=:password");
  $sql_padre->bindParam(':mail', $input['usuario']);
  $sql_padre->bindParam(':password',$input['password']);
  $sql_padre->setFetchMode(PDO::FETCH_ASSOC);
  $sql_padre->execute();
  $fila_padre= $sql_padre->fetch();

  //////////////////////////////////////////////
  //Buscamos en la tabla usuario_administrador//
  //////////////////////////////////////////////

  $sql_administrador = $dbConn->prepare("SELECT nombre FROM usuario_administrador where mail=:mail and password=:password");
  $sql_administrador->bindParam(':mail', $input['usuario']);
  $sql_administrador->bindParam(':password',$input['password']);
  $sql_administrador->setFetchMode(PDO::FETCH_ASSOC);
  $sql_administrador->execute();
  $fila_administrador = $sql_administrador->fetch();

  if (isset($fila_hijo['alias']))
  {
    header("HTTP/1.1 200 OK");
    session_start();//Inicia la sesión
    $_SESSION['username'] = $fila_hijo['alias']; 
    $input['page'] = "HIJO";
    $input['user'] = $_SESSION['username'];
    echo json_encode($input);
    exit();
  }
  elseif(isset($fila_padre['nombre']))
  {
    header("HTTP/1.1 200 OK");
    session_start();//Inicia la sesión
    $_SESSION['username'] = $fila_padre['nombre'];    
    $input['page'] = "PADRE";
    $input['user'] = $_SESSION['username']; 
    echo json_encode($input);
    exit();
  }
  elseif(isset($fila_administrador['nombre']))
  {
    header("HTTP/1.1 200 OK");
    session_start();//Inicia la sesión
    $_SESSION['username'] = $fila_administrador['nombre'];
    $input['page'] = "ADMINISTRADOR";
    $input['user'] = $_SESSION['username'];
    echo json_encode($input);
    exit();
  }
  else
  {
    $input['page'] = "NINGUNA";
    echo json_encode($input);
    exit();
  } 
  
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
