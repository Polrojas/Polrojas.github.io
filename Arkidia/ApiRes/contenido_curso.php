<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
////////////////////////////////////
//  CONSULTA DE TABLA CATEGORIA  //
/////////////////////////////////// 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if(isset($_GET['usuario']))
  {   $evento="";
      if (isset($_GET['id_curso'])) // Si el campo categoría existe es una búsqueda puntual
      {
          //Mostrar una categoría particular
        if(!empty($_GET['id_curso'])) // Si el campo no está vacío
        {
          try{
          $sql = $dbConn->prepare("SELECT * FROM contenido_curso where id_curso=:id_curso order by orden");
          $sql->bindValue(':id_curso', $_GET['id_curso']);
          $sql->execute();            
          $sql->setFetchMode(PDO::FETCH_ASSOC);
          header("HTTP/1.1 200 OK");          
            if(empty($sql)){
              $respuesta['resultado']="ERROR";
              $respuesta['mensaje']="La categoría NO existe en la tabla.";
              echo json_encode(  $respuesta  );            
            }else{ 
              $evento = "Consulta el contenido del curso" ;
              header("HTTP/1.1 200 OK");
              echo json_encode(  $sql->fetchAll()  );          
            }
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
// ALTA DE CONTENIDO //
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
  }elseif(!isset($input['orden']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el orden de contenido por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['nombre_contenido']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el nombre_contenido por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['url_contenido']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el campo url_contenido por POST.";    
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
    }elseif(empty($input['orden']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el número de orden del contenido.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['nombre_contenido']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el nombre del contenido.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['url_contenido']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar la URL del video.";    
      echo json_encode($respuesta);
      exit();   
    }else
    {
      //Busca la descripción en la tabla
      $consulta = $dbConn->prepare("SELECT * FROM contenido_curso where id_curso=:id_curso and orden=:orden");
      $consulta->bindValue(':id_curso', $input['id_curso']);
      $consulta->bindValue(':orden', $input['orden']);
      $consulta->execute();
      $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
      if(!empty($fila_consulta['nombre_contenido']))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="El contenido nro. " . $fila_consulta['orden'] . " ya existe en la tabla.";    
        echo json_encode($respuesta);
        exit();
      }else
      {
        try{
          $sql = "INSERT INTO contenido_curso
                (orden, id_curso, nombre_contenido, url_contenido)
                VALUES
                (:orden, :id_curso, :nombre_contenido, :url_contenido)";          
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':orden', $input['orden']);
          $statement->bindParam(':id_curso', $input['id_curso']);
          $statement->bindParam(':nombre_contenido', $input['nombre_contenido']);
          $statement->bindParam(':url_contenido', $input['url_contenido']);
          $statement->execute();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="$e";
          echo json_encode(  $respuesta  ); 
        }      
        $id_contenido = $dbConn->lastInsertId();

        if($id_contenido)
        {
          //Agrega el registro en el log de eventos
          $evento = "ALTA DE CONTENIDO ".$input['nombre_contenido'];    
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
          $respuesta['id_contenido'] = $id_contenido;
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
    if(!isset($_GET['id_contenido']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_contenido por POST.";
      echo json_encode(  $respuesta  );
      exit();         
    }elseif(!isset($_GET['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario por POST.";
      echo json_encode(  $respuesta  );
      exit();        
    }elseif(empty($_GET['id_contenido']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_contenido para eliminar.";
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
        $id_contenido = $_GET['id_contenido'];
        //$orden=$_GET['orden'];
        //busca la descripción en la tabla
        $consulta = $dbConn->prepare("SELECT * FROM contenido_curso WHERE id_contenido = :id_contenido");
        $consulta->bindValue(':id_contenido', $id_contenido);        
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!empty($fila_consulta)) 
        {
          $evento = "ELIMINO EL CONTENIDO ".$fila_consulta['nombre_contenido'];
          $statement = $dbConn->prepare("DELETE FROM contenido_curso WHERE id_contenido = :id_contenido");
          $statement->bindValue(':id_contenido', $id_contenido);          
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