<?php
require_once "config.php";
require_once "utils.php";
require_once "SegCla.php";
$dbConn =  connect($db);
//Muestra el contenido de la tabla hijos
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $evento="";
    if (isset($_GET['usuario']))
    /////////////////////////////////////////////////////////////////////////////////////////////
    //Para hacer esta consulta se deberá cargar un parámetro "usuario" que representa a un hijo//
    ////////////////////////////////////////////////////////////////////////////////////////////
    {
      try{          
          $sql = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");          
          $sql->bindValue(':usuario', $_GET['usuario']);      
          $sql->execute();
          $fila_hijo = $sql->fetch(PDO::FETCH_ASSOC);
          header("HTTP/1.1 200 OK");
          if($fila_hijo['usuario']==""){
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']="El usuario NO existe en la tabla.";
            echo json_encode(  $respuesta  );
            exit();
          }else{
            if(isset($_GET['accion']) == "avatar")
            {
              $respuesta['avatar']           = $fila_hijo['avatar'];
              echo json_encode(  $respuesta  );
              
            }else
            {
              $respuesta['usuario']          = $fila_hijo['usuario'];
              $respuesta['alias']            = $fila_hijo['alias'];
              $respuesta['usuario_padre']    = $fila_hijo['usuario_padre'];
              $respuesta['fecha_nacimiento'] = $fila_hijo['fecha_nacimiento'];
              $respuesta['edad']             = calculaEdad($fila_hijo['fecha_nacimiento']);
              $respuesta['password']         = desencriptar($fila_hijo['password']);
              $respuesta['avatar']           = $fila_hijo['avatar'];
              echo json_encode(  $respuesta  );
              $evento = "CONSULTA DEL HIJO: " . $_GET['usuario'];
            }  
          }
      }catch (Exception $e){
        $e->getMessage();          
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="La tabla NO existe.";
        echo json_encode(  $respuesta  );      
      }
    }
    elseif (isset($_GET['usuario_padre'])) 
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Para hacer esta consulta se deberá cargar un parámetro "usuario_padre" para obtener a todos los hijos//
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    {
      try{
        $sql = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario_padre=:usuario_padre");
        $sql->bindValue(':usuario_padre', $_GET['usuario_padre']);
        $sql->execute();  
        $set_datos = $sql->fetchAll();  
        $fila_hijo['usuario_padre']=$_GET['usuario_padre']; 
        $respuesta=array();
        foreach($set_datos as $row)
        {
            $item = array(
            'usuario'          => $row['usuario'],
            'alias'            => $row['alias'],
            'usuario_padre'    => $row['usuario_padre'],
            'fecha_nacimiento' => $row['fecha_nacimiento'],
            'edad'             => calculaEdad($row['fecha_nacimiento']),
            'password'         => desencriptar($row['password']),
            'avatar'           => $row['avatar']          
          );
          array_push($respuesta, $item);
        }
        
        
        header("HTTP/1.1 200 OK");
        echo json_encode( $respuesta );
        $evento = "CONSULTA TODOS SU HIJOS";
      }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="La tabla NO existe.";
          echo json_encode(  $respuesta  ); 
        }
    }
    else
    {
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////
      //Para hacer esta consulta se deberá cargar un parámetro "administrador" para obtener a todos los registro//
      ///////////////////////////////////////////////////////////////////////////////////////////////////////////
     try{       
          $sql = $dbConn->prepare("SELECT * FROM usuario_hijo");          
          $sql->execute();
          //$sql->setFetchMode(PDO::FETCH_ASSOC);          
          $fila_hijo=$sql->fetchAll();
          if (count($fila_hijo) == 0)
          {
              $respuesta['resultado']="OK";
              $respuesta['mensaje']="";
              echo json_encode(  $respuesta  );
              exit();          
          }                   
          $respuesta = array();
          //$respuesta['Items']= array(); 
        
          foreach($fila_hijo as $row)
          {
              $item = array(
              'usuario'          => $row['usuario'],
              'alias'            => $row['alias'],
              'usuario_padre'    => $row['usuario_padre'],
              'fecha_nacimiento' => $row['fecha_nacimiento'],
              'edad'             => calculaEdad($row['fecha_nacimiento']),
              'password'         => desencriptar($row['password']),
              'avatar'           => $row['avatar']          
            );
            array_push($respuesta, $item);
          }
          $sql=null;
               
        header("HTTP/1.1 200 OK");        
        echo json_encode( $respuesta );
        $evento = "CONSULTA TODOS LOS USUARIOS HIJOS";
        $fila_hijo['usuario_padre'] = $_GET['administrador'];
      }catch(Exception $e)
      {
        $e->getMessage();          
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="La tabla NO existe.";
        echo json_encode(  $respuesta  ); 
      }      
    }
    //LOG DE CONSULTA
    if($evento != ""){ 
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
      $statement->bindParam(':usuario', $fila_hijo['usuario_padre']);          
      $statement->execute();
    }
    exit();
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
      //$password=password_hash($input['password'], PASSWORD_DEFAULT, array("cost"=>12));
      $password = encriptar($input['password']);
      $sql = "INSERT INTO usuario_hijo
            (usuario, alias, usuario_padre, fecha_nacimiento, password, avatar)
            VALUES
            (:usuario, :alias, :usuario_padre, :fecha_nacimiento, :password, :avatar)";
      $statement = $dbConn->prepare($sql);      
      $statement->bindParam(':usuario', $input['usuario']);
      $statement->bindParam(':alias', $input['alias']);
      $statement->bindParam(':usuario_padre', $input['usuario_padre']);
      $statement->bindParam(':fecha_nacimiento', $input['fecha']);
      $statement->bindParam(':password', $password);
      $statement->bindParam(':avatar', $input['avatar']); 
      $statement->execute();
      //graba_log("SE REGISTRO EN EL SISTEMA",$usuario)
      $evento = "ALTA DEL HIJO ".$input['usuario'];    
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
    $clave=encriptar($input['password']);
    //Se busca si existe el usuario
    $sql_hijo = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");
    $sql_hijo->bindParam(':usuario', $input['usuario']); 
    $sql_hijo->setFetchMode(PDO::FETCH_ASSOC);
    $sql_hijo->execute();
    $fila_hijo= $sql_hijo->fetch();
    try{
      $input['password']=$clave;
      $fields = getParams($input);
/*      $data=[
        'usuario' => $input['usuario'],
        'alias' => $input['alias'],      
        'fecha_nacimiento' => $input['fecha'],
        'password' => encriptar($input['password']),
        'avatar' => $input['avatar'],
      ];
      $sql = "UPDATE usuario_hijo 
            SET alias = :alias, fecha_nacimiento = :fecha_nacimiento, password = :password, avatar = :avatar
            WHERE usuario=:usuario";*/
      $sql = "UPDATE usuario_hijo SET $fields WHERE usuario='$usuario'";
      $statement = $dbConn->prepare($sql);
      bindAllValues($statement, $input);
          
      $statement->execute();      
    }catch(Exception $e)
    {
      $e->getMessage();          
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']=$e;
      echo json_encode(  $respuesta  );
      exit();
    } 
    $evento = "MODIFICO LOS DATOS DEL HIJO: " . $input['usuario'];    
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