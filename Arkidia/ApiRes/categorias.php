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
      if (isset($_GET['id_categoria'])) // Si el campo categoría existe es una búsqueda puntual
      {
          //Mostrar una categoría particular
        if(!empty($_GET['id_categoria'])) // Si el campo no está vacío
        {
          try{
            $sql = $dbConn->prepare("SELECT * FROM categorias where id_categoria=:id_categoria");
            $sql->bindValue(':id_categoria', $_GET['id_categoria']);
            $sql->execute();
            $fila_categoria = $sql->fetch(PDO::FETCH_ASSOC);
            if(empty($sql)){
              $respuesta['resultado']="ERROR";
              $respuesta['mensaje']="La categoría NO existe en la tabla.";
              echo json_encode(  $respuesta  );            
            }else{ 
              $evento = "Consulta la categoría " . $fila_categoria['descripcion'];
              header("HTTP/1.1 200 OK");
              echo json_encode(  $fila_categoria  );          
            }
          }catch (Exception $e){
            $e->getMessage();          
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']=$e;
            echo json_encode(  $respuesta  );      
          }
        }else{
          $respuesta['resultado']="OK";
          $respuesta['mensaje']="s";
          echo json_encode(  $respuesta  );               
        }
      }else {
        //Mostrar lista de categorías completa
        try{
          $evento = "Consulta todas las categoría.";
          $sql = $dbConn->prepare("SELECT * FROM categorias");
          $sql->execute();
          $sql->setFetchMode(PDO::FETCH_ASSOC);
          header("HTTP/1.1 200 OK");
          echo json_encode( $sql->fetchAll()  );
        }catch (Exception $e){
          $e->getMessage();        
          $evento = "";  
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']=$e;
          echo json_encode(  $respuesta  );      
        }      

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

////////////////////////
// ALTA DE CATEGORIA //
///////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $input = $_POST;

  if(empty($input))//Si no envia ningún campo por POST
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar los campos: descripcion - imagen_categoria - color - link_video.";    
    echo json_encode($respuesta);
    exit();    
  //Chequeo que envíe todos los campos que necesita la API para hacer el insert
  }elseif(!isset($input['descripcion']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar la descripción por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['imagen_categoria']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar la imagen por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['color']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el color por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['link_video']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el campo link_video por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['usuario']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el campo usuario por POST.";    
    echo json_encode($respuesta);
    exit();      
  }else
  {
    if(empty($input['descripcion']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar la descripción de la categoría.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['imagen_categoria']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar la ruta de la imagen.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['color']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el color de la categoría.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['link_video']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar la URL en donde se encuentra el video de la categoría.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el usuario administrador.";    
      echo json_encode($respuesta);
      exit();        
    }else
    {
      //Busca la descripción en la tabla
      $consulta = $dbConn->prepare("SELECT * FROM categorias where descripcion=:descripcion");
      $consulta->bindValue(':descripcion', $input['descripcion']);
      $consulta->execute();
      $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
      if(!empty($fila_consulta['descripcion']))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="La categoría " . $fila_consulta['descripcion'] . " ya existe en la tabla.";    
        echo json_encode($respuesta);
        exit();
      }else
      {
        try{
          $sql = "INSERT INTO categorias
                (descripcion, imagen_categoria, color, link_video, estado)
                VALUES
                (:descripcion, :imagen_categoria, :color, :link_video, :estado)";
          $color = "#".$input['color'];
          $estado = "B";
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':descripcion', $input['descripcion']);
          $statement->bindParam(':imagen_categoria', $input['imagen_categoria']);
          $statement->bindParam(':color', $color);
          $statement->bindParam(':link_video', $input['link_video']);
          $statement->bindParam(':estado', $estado);
          $statement->execute();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']=$e;
          echo json_encode(  $respuesta  ); 
        }      
        $postId = $dbConn->lastInsertId();

        if($postId)
        {
          //Agrega el registro en el log de eventos
          $evento = "ALTA DE CATEGORIA ".$input['descripcion'];    
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
          echo json_encode($respuesta);
          exit();
        }
      }
    }
  }    
}

////////////////////////////
// ELIMINAR UNA CATEGORIA //
///////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{


    if(!isset($_GET['id_categoria']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_categoria por POST.";
      echo json_encode(  $respuesta  );
      exit();      
    }elseif(!isset($_GET['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario por POST.";
      echo json_encode(  $respuesta  );
      exit();        
    }elseif(empty($_GET['id_categoria']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_categoria para eliminar.";
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
        $id = $_GET['id_categoria'];
        //busca la descripción en la tabla
        $consulta = $dbConn->prepare("SELECT * FROM categorias where id_categoria=:id_categoria");
        $consulta->bindValue(':id_categoria', $id);
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC); 
        if (!empty($fila_consulta)) 
        {
          $evento = "ELIMINO LA CATEGORIA ".$fila_consulta['descripcion'];
          $statement = $dbConn->prepare("DELETE FROM categorias where id_categoria=:id_categoria");
          $statement->bindValue(':id_categoria', $id);
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
          $respuesta['mensaje']="La categoría no existe en la tabla.";    
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

////////////////////////////
// ACTUALIZACION DE DATOS //
///////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
  if(!isset($_GET['id_categoria']))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar por POST el id_categoria.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(empty($_GET['id_categoria']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el id_categoria.";
    echo json_encode(  $respuesta  );
    exit();
  }else
  {
    $input = $_GET;
    $categoria = $input['id_categoria'];
    $consulta = $dbConn->prepare("SELECT * FROM categorias where id_categoria=:id_categoria");
    $consulta->bindValue(':id_categoria', $categoria);
    $consulta->execute();
    $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);        
    $evento = "MODIFICO LA CATEGORIA ".$fila_consulta['descripcion'];
  } 
  if(!isset($input['usuario']))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar por POST el usuario.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(empty($_GET['usuario']))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el usuario.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(!empty($fila_consulta))
  {
    try{
 
      $data=[
        'id_categoria' => $input['id_categoria'],
        'descripcion' => $input['descripcion'],
        'imagen_categoria' => $input['imagen_categoria'],
        'color' => '#'.$input['color'],
        'link_video' => $input['link_video'],
        'estado' => $input['estado'],
      ];
      $sql = "UPDATE categorias 
      SET descripcion = :descripcion, imagen_categoria = :imagen_categoria,
      color = :color, link_video = :link_video, estado = :estado
      WHERE id_categoria = :id_categoria";
      $statement = $dbConn->prepare($sql);
     
      $statement->execute($data); 
    }catch(Exception $e)
    {
      $e->getMessage();          
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']=$e;
      echo json_encode(  $respuesta  );
      exit();
    } 
    //Agrega el registro en el log de eventos    
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha_formateada = date("Y-m-d H:i:s",time());
    $evento = "MODIFICO LA CATEGORIA ". $fila_consulta['descripcion'];
    //Graba registro en tabla Log
    try{
      $sql = "INSERT INTO log 
      (fecha, evento, usuario)
      VALUES
      (:fecha, :evento, :usuario)";
      $statement = $dbConn->prepare($sql);  
      $statement->bindParam(':fecha', $fecha_formateada);
      $statement->bindParam(':evento', $evento);
      $statement->bindParam(':usuario', $_GET['usuario']);          
      $statement->execute();
    }catch(Exception $e)
    {
      $e->getMessage();          
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']=$e;
      echo json_encode(  $respuesta  );
      exit();
    }          
    $respuesta['resultado']="OK";
    $respuesta['mensaje']="";    
    echo json_encode($respuesta);
    header("HTTP/1.1 200 OK");
    exit();
  }else
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="La categoría NO existe.";
    echo json_encode(  $respuesta  );
    exit();
  }   
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
