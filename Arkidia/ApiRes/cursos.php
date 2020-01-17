<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
///////////////////////////
//  CONSULTA DE CURSOS  // 
////////////////////////// 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  $evento="";
  if (isset($_GET['id_curso'])) // Si el campo id_curso existe es una búsqueda puntual
  {
      //Mostrar un curso particular
    if(!empty($_GET['id_curso'])) // Si el campo no está vacío
    {
      try{
        $sql = $dbConn->prepare("SELECT * FROM cursos where id_curso=:id_curso");
        $sql->bindValue(':id_curso', $_GET['id_curso']);
        $sql->execute();
        $fila_curso = $sql->fetch(PDO::FETCH_ASSOC);
        if(empty($fila_curso)){
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="El curso NO existe en la tabla.";
          echo json_encode(  $respuesta  );            
        }else{
          $evento = "Consulta el curso " . $fila_curso['nombre_curso'];
          header("HTTP/1.1 200 OK");
          echo json_encode(  $fila_curso);
        }
      }catch (Exception $e){
        $e->getMessage();          
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']=$e;
        echo json_encode(  $respuesta  );      
      }
    }else{
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe ingresar un valor para la identificación de curso.";
      echo json_encode(  $respuesta  );               
    }
  //Consulta toda la tabla de cursos para mostrar al admistrador
  }elseif(isset($_GET['usuario']) && !isset($_GET['id_categoria']))
  {
    if(!empty($_GET['usuario']))
    {
      try{
        $evento = "Consulta todos los cursos.";
        $sql = $dbConn->prepare("SELECT `cursos`.*, `categorias`.`descripcion`, `proveedores`.`nombre_proveedor`
              FROM `cursos`
              , `categorias`
              , `proveedores`
            WHERE `cursos`.`id_categoria` = `categorias`.`id_categoria` 
            AND  `cursos`.`id_proveedor` = `proveedores`.`id_proveedor`");
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
        exit();   
      } 
    }else
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe ingresar el usuario.";
      echo json_encode(  $respuesta  );
      exit();   
    }
  }elseif(isset($_GET['usuario']) && isset($_GET['id_categoria']))
  {
    //Consuta por categorías dependiendo el usuario
    $sql_hijo = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");
    $sql_hijo->bindParam(':usuario',$_GET['usuario']);  
    $sql_hijo->setFetchMode(PDO::FETCH_ASSOC);
    $sql_hijo->execute();
    $fila_hijo= $sql_hijo->fetchAll();
    $datos=$sql_hijo->setFetchMode(PDO::FETCH_ASSOC);

    $sql_padre = $dbConn->prepare("SELECT * FROM usuario_padre where mail=:mail");
    $sql_padre->bindParam(':mail', $_GET['usuario']);  
    $sql_padre->setFetchMode(PDO::FETCH_ASSOC);
    $sql_padre->execute();
    $fila_padre= $sql_padre->fetchAll();    

    if(!empty($fila_hijo))
    {
        $estado="P";
        $sql = $dbConn->prepare("SELECT *
            FROM cursos
            WHERE id_categoria = :id_categoria AND (edad_desde >= :edad OR edad_hasta <= :edad)
            AND estado_curso = :estado_curso");
        $sql->bindValue(':id_categoria', $_GET['id_categoria']);
        $sql->bindValue(':edad', calculaEdad($datos['fecha_nacimiento']));
        $sql->bindValue(':estado_curso', $estado);
        $sql->execute();        
        $cursos = $sql->fetchAll();

        $respuesta=array();

        foreach($cursos as $row)
        {   
                  $sql = $dbConn->prepare("SELECT orden, url_imagen
                        FROM contenido_curso
                        WHERE id_curso = :id_curso");
                  $sql->bindValue(':id_curso', $row['id_curso']);        
                  $sql->execute();
                  $sql->setFetchMode(PDO::FETCH_ASSOC);               
                  $contenido = $sql->fetchAll();
                  $item = array(
                  'id_curso'      => $row['id_curso'],
                  'nombre_curso'  => $row['nombre_curso'],
                  'detalle_curso' => $row['detalle_curso'],
                  'edad_desde'    => $row['edad_desde'],
                  'edad_hasta'    => $row['edad_hasta'],
                  'likes'         => "0",
                  'comentarios'   => "0",

                  'contenido'     => $contenido             
                  );
                  array_push($respuesta, $item);
        }
        header("HTTP/1.1 200 OK");
        echo json_encode( $respuesta  );
    }
    elseif ($fila_padre) {
        $estado="P";
        $sql = $dbConn->prepare("SELECT *
            FROM cursos
            WHERE id_categoria = :id_categoria AND estado_curso = :estado_curso");
        $sql->bindValue(':id_categoria', $_GET['id_categoria']);        
        $sql->bindValue(':estado_curso', $estado);
        $sql->execute();
        $cursos = $sql->fetchAll();

        $respuesta=array();
        foreach($cursos as $row)
        {
                  $sql = $dbConn->prepare("SELECT orden, url_imagen
                        FROM contenido_curso
                        WHERE id_curso = :id_curso");
                  $sql->bindValue(':id_curso', $row['id_curso']);        
                  $sql->execute();
                  $sql->setFetchMode(PDO::FETCH_ASSOC);
                  $contenido = $sql->fetchAll();          
                  $item = array(
                  'id_curso'      => $row['id_curso'],
                  'nombre_curso'  => $row['nombre_curso'],
                  'detalle_curso' => $row['detalle_curso'],
                  'edad_desde'    => $row['edad_desde'],
                  'edad_hasta'    => $row['edad_hasta'],
                  'likes'         => "0",
                  'comentarios'   => "0",
                  'contenido'     => $contenido
                  );
                  array_push($respuesta, $item);
        }
        header("HTTP/1.1 200 OK");
        echo json_encode( $respuesta  );
    }else{
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="El usuario no es ni hijo ni padre.";
      echo json_encode(  $respuesta  );
      exit();      
    }
  //Muestra todo pero sin el ingreso de usuario para la página principal    
  }elseif(!isset($_GET['usuario']) && isset($_GET['id_categoria']))
  {
        $estado="P";
        $sql = $dbConn->prepare("SELECT *
            FROM cursos
            WHERE id_categoria = :id_categoria AND estado_curso = :estado_curso");
        $sql->bindValue(':id_categoria', $_GET['id_categoria']);        
        $sql->bindValue(':estado_curso', $estado);
        $sql->execute();
        $cursos = $sql->fetchAll();

        $respuesta=array();
        foreach($cursos as $row)
        {
                  $sql = $dbConn->prepare("SELECT orden, url_imagen
                        FROM contenido_curso
                        WHERE id_curso = :id_curso");
                  $sql->bindValue(':id_curso', $row['id_curso']);        
                  $sql->execute();
                  $sql->setFetchMode(PDO::FETCH_ASSOC);
                  $contenido = $sql->fetchAll();          
                  $item = array(
                  'id_curso'      => $row['id_curso'],
                  'nombre_curso'  => $row['nombre_curso'],
                  'detalle_curso' => $row['detalle_curso'],
                  'edad_desde'    => $row['edad_desde'],
                  'edad_hasta'    => $row['edad_hasta'],
                  'likes'         => "0",
                  'comentarios'   => "0",
                  'contenido'     => $contenido
                  );
                  array_push($respuesta, $item);
        }
        header("HTTP/1.1 200 OK");
        echo json_encode( $respuesta  );    
  }
  //Se registrará cuando exista un evento ejecutado por un usuario
  if($evento != "") { 
    if(isset($_GET['usuario']) && $_GET['usuario'] != "" )
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

}

////////////////////
// ALTA DE CURSO //
///////////////////
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
  }elseif(!isset($input['id_categoria']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar id_categoria por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['nombre_curso']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el nombre_curso por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['detalle_curso']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el detalle_curso por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['edad_desde']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el campo edad_desde por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['edad_hasta']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el campo edad_hasta por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['id_proveedor']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el campo id_proveedor por POST.";    
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
    if(empty($input['id_categoria']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar la categoría del curso.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['nombre_curso']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el nombre del curso.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['detalle_curso']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el detalle del curso.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['edad_desde']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar edad desde la cual se propone el curso.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['edad_hasta']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar edad hasta la cual se propone el curso.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['id_proveedor']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el proveedor del curso.";    
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
      $consulta = $dbConn->prepare("SELECT * FROM cursos where nombre_curso=:nombre_curso");
      $consulta->bindValue(':nombre_curso', $input['nombre_curso']);
      $consulta->execute();
      $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
      if(!empty($fila_consulta['nombre_curso']))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="El curso " . $fila_consulta['nombre_curso'] . " ya existe en la tabla.";    
        echo json_encode($respuesta);
        exit();
      }else
      {
        try{
          $sql = "INSERT INTO cursos
                (id_categoria, nombre_curso, detalle_curso, edad_desde, edad_hasta, id_proveedor, estado_curso)
                VALUES
                (:id_categoria, :nombre_curso, :detalle_curso, :edad_desde, :edad_hasta, :id_proveedor, :estado_curso)";
          $estado = "B";
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':id_categoria', $input['id_categoria']);
          $statement->bindParam(':nombre_curso', $input['nombre_curso']);
          $statement->bindParam(':detalle_curso', $input['detalle_curso']);
          $statement->bindParam(':edad_desde', $input['edad_desde']);   
          $statement->bindParam(':edad_hasta', $input['edad_hasta']);
          $statement->bindParam(':id_proveedor', $input['id_proveedor']);
          $statement->bindParam(':estado_curso', $estado);
          $statement->execute();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']=$e;
          echo json_encode(  $respuesta  ); 
        }      
        $id_curso = $dbConn->lastInsertId();

        if($id_curso)
        {
          //Agrega el registro en el log de eventos
          $evento = "ALTA DE CURSO ".$input['nombre_curso'];    
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
          $respuesta['curso'] = $id_curso;
          echo json_encode($respuesta);
          exit();
        }
      }
    }
  }    
}

////////////////////////
// ELIMINAR UN CURSO //
///////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    if(!isset($_GET['id_curso']))
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
    }elseif(empty($_GET['id_curso']))
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
        $id = $_GET['id_curso'];
        //busca la descripción en la tabla
        $consulta = $dbConn->prepare("SELECT * FROM cursos where id_curso=:id_curso");
        $consulta->bindValue(':id_curso', $id);
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC); 
        if (!empty($fila_consulta)) 
        {
          $evento = "ELIMINO EL CURSO ".$fila_consulta['nombre_curso'];
          $statement = $dbConn->prepare("DELETE FROM cursos where id_curso=:id_curso");
          $statement->bindValue(':id_curso', $id);
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

////////////////////////////
// ACTUALIZACION DE DATOS //
///////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
  if(!isset($_GET['id_curso']))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar por POST el id_curso.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(empty($_GET['id_curso']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el id_curso.";
    echo json_encode(  $respuesta  );
    exit();
  }else
  {
    $input = $_GET;
    $curso = $input['id_curso'];
    $consulta = $dbConn->prepare("SELECT * FROM cursos where id_curso=:id_curso");
    $consulta->bindValue(':id_curso', $curso);
    $consulta->execute();
    $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);        
    $evento = "MODIFICO EL CURSO ".$fila_consulta['nombre_curso'];
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
        'id_curso'=> $input['id_curso'],
        'id_categoria' => $input['id_categoria'],
        'nombre_curso' => $input['nombre_curso'],
        'detalle_curso' => $input['detalle_curso'],
        'edad_desde' => $input['edad_desde'],
        'edad_hasta' => $input['edad_hasta'],
        'id_proveedor' => $input['id_proveedor'],
        'estado_curso' => $input['estado_curso'],
      ];
      $sql = "UPDATE cursos 
      SET id_categoria = :id_categoria, nombre_curso = :nombre_curso, detalle_curso = :detalle_curso,
      edad_desde = :edad_desde, edad_hasta = :edad_hasta, id_proveedor = :id_proveedor, estado_curso = :estado_curso
      WHERE id_curso = :id_curso";
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
    $evento = "MODIFICO EL CURSO ". $fila_consulta['nombre_curso'];
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
    $respuesta['mensaje']="El curso NO existe.";
    echo json_encode(  $respuesta  );
    exit();
  }   
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>