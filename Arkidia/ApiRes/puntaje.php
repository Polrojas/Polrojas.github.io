<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);

/////////////////////////////////
//  CONSULTA DE TABLA PUNTAJE  //
///////////////////////////////// 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if(isset($_GET['usuario']) || empty($_GET['usuario']))
  {   
      $evento="";
      if (isset($_GET['evento'])) // Si el campo evento existe es una búsqueda puntual
      {
          //Mostrar una evento particular
        if(!empty($_GET['evento'])) // Si el campo no está vacío
        {
          try{
            $sql = $dbConn->prepare("SELECT * FROM puntaje where evento = :evento");
            $sql->bindValue(':evento', $_GET['evento']);
            $sql->execute();
            $dato=$sql->fetch(PDO::FETCH_ASSOC);
            $evento = "Consulta el evento ". $dato['evento'] ;

            if(!empty($dato)){
              header("HTTP/1.1 200 OK");
              echo json_encode(  $dato  );
            }else
            {
              $respuesta['resultado']="ERROR";
              $respuesta['mensaje']="No existe el evento en la tabla.";
              echo json_encode(  $respuesta  );
              exit();             
            }
          }catch (Exception $e){
            $e->getMessage();          
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']=$e;
            echo json_encode(  $respuesta  );      
          }
        }else{
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="Debe completar el evento";
          echo json_encode(  $respuesta  );               
        }
      }else {
          try{
            $sql = $dbConn->prepare("SELECT * FROM puntaje");           
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $evento = "Consulta todos los eventos" ;
            header("HTTP/1.1 200 OK");
            echo json_encode(  $sql->fetchAll()  );
          }catch (Exception $e){
            $e->getMessage();          
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']=$e;
            echo json_encode(  $respuesta  );
            exit();     
          }
      }
      //Se registrará cuando exista un evento ejecutado por un usuario
      date_default_timezone_set('America/Argentina/Buenos_Aires');
      $fecha_formateada = date("Y-m-d H:i:s",time());
      $usuario=$_GET['usuario'];  
      //Graba registro en tabla Log
      $sql = "INSERT INTO log 
      (fecha, evento, usuario)
      VALUES
      (:fecha, :evento, :usuario)";
      $statement = $dbConn->prepare($sql);     
      $statement->bindParam(':fecha', $fecha_formateada);
      $statement->bindParam(':evento', $evento);
      $statement->bindParam(':usuario', $usuario);          
      $statement->execute();
      exit();     
  }else{
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el mail del administrador.";
      echo json_encode(  $respuesta  );
      exit(); 
  }
}


//////////////////////
// ALTA DE PUNTAJES //
//////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $input = $_POST;

  if(empty($input))//Si no envia ningún campo por POST
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar los campos para el alta.";    
    echo json_encode($respuesta);
    exit();    
  //Chequeo que envíe todos los campos que necesita la API para hacer el insert
  }elseif(!isset($input['usuario']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar usuario por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['evento']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar evento por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['puntaje']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el puntaje por POST.";    
    echo json_encode($respuesta);
    exit();
  }else
  {
    if(empty($input['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar usuario.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['evento']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el evento.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['puntaje']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el puntaje.";    
      echo json_encode($respuesta);
      exit();   
    }else
    {
      //Busca la descripción en la tabla
      $consulta = $dbConn->prepare("SELECT * FROM puntaje where evento=:evento");
      $consulta->bindValue(':evento', $input['evento']);
      $consulta->execute();
      $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
      if(!empty($fila_consulta))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="El evento " . $fila_consulta['evento'] . " ya existe en la tabla.";    
        echo json_encode($respuesta);
        exit();
      }else
      {
        try{
          $sql = "INSERT INTO puntaje
                (evento, puntaje)
                VALUES
                (:evento, :puntaje)";          
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':evento', $input['evento']);
          $statement->bindParam(':puntaje', $input['puntaje']);          
          $statement->execute();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="$e";
          echo json_encode(  $respuesta  ); 
        }    
        //Agrega el registro en el log de eventos
        $evento = "Alta de evento ". $fila_consulta['evento'];    
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
          $statement->bindParam(':usuario', $input['usuario']);          
          $statement->execute();
          exit();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']=$e;
          echo json_encode(  $respuesta  );
          exit();
        }            
        header("HTTP/1.1 200 OK");
        $respuesta['resultado']="OK";
        $respuesta['mensaje']="";
        echo json_encode($respuesta);
        exit();
      }      
    }   
  }
}

//////////////////////////
// ELIMINAR UN PUNTAJE //
/////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    if(!isset($_GET['evento']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el evento por POST.";
      echo json_encode(  $respuesta  );
      exit();      
    }elseif(!isset($_GET['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario por POST.";
      echo json_encode(  $respuesta  );
      exit();            
    }else
    {        
      try{        
        //busca la descripción en la tabla
        $consulta = $dbConn->prepare("SELECT * FROM puntaje where evento=:evento");
        $consulta->bindValue(':evento', $_GET['evento']);
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC); 
        if (!empty($fila_consulta)) 
        {
          $evento = "Eliminó el evento ".$fila_consulta['evento'];
          $statement = $dbConn->prepare("DELETE FROM puntaje where evento=:evento");
          $statement->bindValue(':evento', $_GET['evento']);
          $statement->execute();
          //Agrega el registro en el log de eventos    
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
          $statement->bindParam(':usuario', $_GET['usuario']);          
          $statement->execute();
          $respuesta['resultado']="OK";
          $respuesta['mensaje']="";    
          echo json_encode($respuesta);
          header("HTTP/1.1 200 OK");
          exit();
        }else
        {
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="El curso no existe en la tabla.";    
          echo json_encode($respuesta);
          exit();        
        }
      }catch(Exception $e)
      {
        $e->getMessage();          
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']=$e;
        echo json_encode(  $respuesta  );
        exit();
      } 
    }    
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    //Se busca si existe el usuario
    $consulta = $dbConn->prepare("SELECT * FROM puntaje where evento=:evento");
    $consulta->bindParam(':evento', $input['evento']); 
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute();
    $fila_consulta= $consulta->fetch();
    try{
      if($fila_consulta)
      {
      $data=[       
        'evento' => $input['evento'],
        'puntaje' => $input['puntaje'],
      ];        
      $sql = "UPDATE puntaje 
      SET evento = :evento, puntaje = :puntaje
      WHERE evento = :evento";
      $statement = $dbConn->prepare($sql);     
      $statement->execute($data);             
      }else
      {
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="El evento no existe en la tabla.";    
          echo json_encode($respuesta); 
          exit();       
      }
 
    }catch(Exception $e)
    {
      $e->getMessage();          
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']=$e;
      echo json_encode(  $respuesta  );
      exit();
    } 
    $evento = "Se modificó el puntaje de: " . $input['evento'];    
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
    $statement->bindParam(':usuario', $input['usuario']);          
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