<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
////////////////////////////////////
//  CONSULTA DE TABLA CATEGORIA  //
/////////////////////////////////// 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if(isset($_GET['usuario']) || empty($_GET['usuario']))
  {   
      $evento="";
      if (isset($_GET['id_curso'])) // Si el campo categoría existe es una búsqueda puntual
      {
          //Mostrar una categoría particular
        if(!empty($_GET['id_curso'])) // Si el campo no está vacío
        {
          try{
            $sql = $dbConn->prepare("SELECT * FROM challenges_cursos where id_curso = :id_curso");
            $sql->bindValue(':id_curso', $_GET['id_curso']);
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $evento = "Consulta los challenges del curso" ;
            header("HTTP/1.1 200 OK");
            echo json_encode(  $sql->fetchAll()  );

          }catch (Exception $e){
            $e->getMessage();          
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']=$e;
            echo json_encode(  $respuesta  );      
          }
        }else{
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="Debe completar el id_curso";
          echo json_encode(  $respuesta  );               
        }
      }else {
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="Debe enviar por POST el id_curso.";
          echo json_encode(  $respuesta  );
      }
      //Se registrará cuando exsita un evento ejecutado por un usuario
      if($evento != "") { 
        if($_GET['usuario'] != "aplicacion")
        {
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
        }
      }   
      exit();
  }else{
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="Debe indicar el mail del administrador.";
        echo json_encode(  $respuesta  );   
  }
}


///////////////////////
// ALTA DE CHALLENGE //
///////////////////////
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
  }elseif(!isset($input['id_curso']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar id_curso por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['orden_challenge']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar orden_challenge por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['nombre_challenge']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el nombre_challenge por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['detalle_challenge']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el campo detalle_challenge por POST.";    
    echo json_encode($respuesta);
    exit();
  }else
  {
    if(empty($input['id_curso']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar id del curso.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['orden_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el número de orden de challenge.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['nombre_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el nombre del challenge.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['detalle_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el detalle del challenge.";    
      echo json_encode($respuesta);
      exit();   
    }else
    {
      //Busca la descripción en la tabla
      $consulta = $dbConn->prepare("SELECT * FROM challenges_cursos where id_curso=:id_curso and orden_challenge=:orden_challenge");
      $consulta->bindValue(':id_curso', $input['id_curso']);
      $consulta->bindValue(':orden_challenge', $input['orden_challenge']);
      $consulta->execute();
      $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
      if(!empty($fila_consulta['nombre_challenge']))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="El challenge " . $fila_consulta['orden_challenge'] . " ya existe en la tabla.";    
        echo json_encode($respuesta);
        exit();
      }else
      {
        try{
          $sql = "INSERT INTO challenges_cursos
                (orden_challenge, id_curso, nombre_challenge, detalle_challenge)
                VALUES
                (:orden_challenge, :id_curso, :nombre_challenge, :detalle_challenge)";          
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':orden_challenge', $input['orden_challenge']);
          $statement->bindParam(':id_curso', $input['id_curso']);
          $statement->bindParam(':nombre_challenge', $input['nombre_challenge']);
          $statement->bindParam(':detalle_challenge', $input['detalle_challenge']);
          $statement->execute();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="$e";
          echo json_encode(  $respuesta  ); 
        }      
        $id_challenge = $dbConn->lastInsertId();

        if($id_challenge)
        {
          //Agrega el registro en el log de eventos
          $evento = "ALTA DE CHALLENGE ".$input['nombre_challenge'];    
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
          }catch(Exception $e)
          {
            $e->getMessage();          
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']=$e;
            echo json_encode(  $respuesta  ); 
          }            
          header("HTTP/1.1 200 OK");
          $respuesta['resultado']="OK";
          $respuesta['mensaje']="";
          $respuesta['id_challenge']=$id_challenge;
          echo json_encode($respuesta);
          exit();
        }
      }
    }
  } 
}

///////////////////////////
// ELIMINAR UN CONTENIDO //
//////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    if(!isset($_GET['id_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_curso por POST.";
      echo json_encode(  $respuesta  );
      exit();        
    }elseif(!isset($_GET['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario por POST.";
      echo json_encode(  $respuesta  );
      exit();        
    }elseif(empty($_GET['id_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_curso para eliminar.";
      echo json_encode(  $respuesta  );
      exit();              
    }elseif(empty($_GET['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe informar el usuario que realiza la acción.";
      echo json_encode(  $respuesta  );
      exit();            
    }else
    {        
      try{
        $id_challenge = $_GET['id_challenge'];
        
        //busca la descripción en la tabla
        $consulta = $dbConn->prepare("SELECT * FROM challenges_cursos WHERE id_challenge=:id_challenge");
        $consulta->bindValue(':id_challenge', $id_challenge);        
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!empty($fila_consulta)) 
        {
          $evento = "ELIMINO EL CHALLENGE ".$fila_consulta['nombre_challenge'];
          $statement = $dbConn->prepare("DELETE FROM challenges_cursos WHERE id_challenge=:id_challenge ");
          $statement->bindValue(':id_challenge', $id_challenge);         
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
        }
      }catch(Exception $e)
      {
        $e->getMessage();          
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']=$e;
        echo json_encode(  $respuesta  ); 
      } 
    }    
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>