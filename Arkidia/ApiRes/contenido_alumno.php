<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
////////////////////////////
// ACTUALIZACION DE DATOS //
///////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
  $contenido= $_GET['id_contenido'];
  $usuario= $_GET['usuario'];
  $porcentaje_avance = $_GET['porcentaje_avance'];
  if(!isset($contenido))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar por POST el id_contenido.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(empty($contenido))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el id_contenido.";
    echo json_encode(  $respuesta  );
    exit();
  }else
  {
    $consulta = $dbConn->prepare("SELECT * FROM contenido_alumno where id_contenido = :id_contenido and usuario = :usuario");
    $consulta->bindValue(':id_contenido', $contenido);
    $consulta->bindValue('usuario', $usuario);
    $consulta->execute();
    $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);        
    $evento = "MODIFICO EL CURSO ";
  } 
  if(!isset($usuario))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar por POST el usuario.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(!isset($porcentaje_avance))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar por POST el porcentaje_avance.";
      echo json_encode(  $respuesta  );
      exit();    
  }elseif(empty($usuario))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el usuario.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(empty($porcentaje_avance))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el porcentaje_avance.";
      echo json_encode(  $respuesta  );
      exit();    
  }elseif($fila_consulta)
  {
    try{
 
      $data=[
        'id_contenido'=> $contenido,
        'usuario'=>$usuario,
        'porcentaje_avance' => $porcentaje_avance,        
      ];
      $sql = "UPDATE contenido_alumno 
      SET porcentaje_avance = :porcentaje_avance
      WHERE id_contenido = :id_contenido and usuario = :usuario";
      $statement = $dbConn->prepare($sql);     
      $statement->execute($data); 

    //Buscar valor del puntaje que corresponde al video
    if($porcentaje_avance == 100 || $porcentaje_avance == 99 )
    {

      $puntaje = $dbConn->prepare("SELECT * FROM puntaje where evento='video'");
      $puntaje->execute();
      $fila_puntaje = $puntaje->fetch(PDO::FETCH_ASSOC);      
      if(empty($fila_puntaje))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="El evento " . $fila_puntaje['evento'] . " no existe en la tabla de puntaje.";    
        echo json_encode($respuesta);
        exit();
      }else
      {

        $puntos= intval($fila_puntaje['puntaje']);
        //Actualizar el puntaje del alumno
        $puntaje_alumno = $dbConn->prepare("SELECT * FROM puntaje_alumno where usuario=:usuario");
        $puntaje_alumno->bindParam(':usuario', $usuario);
        $puntaje_alumno->execute();
        $fila_puntaje = $puntaje_alumno->fetch(PDO::FETCH_ASSOC);
        $puntos+=$fila_puntaje['puntaje'];          
        $data=[     
        'usuario' => $usuario,
        'puntaje' => $puntos,
        ];        
        $sql = "UPDATE puntaje_alumno
        SET puntaje = :puntaje
        WHERE usuario = :usuario";
        $statement = $dbConn->prepare($sql);     
        $statement->execute($data);
      }
      //Busco el id_curso
      $sql = $dbConn->prepare("SELECT id_curso from contenido_alumno 
        where usuario = :usuario and id_contenido = :id_contenido");
      $sql->bindValue(':usuario', $usuario);
      $sql->bindValue(':id_contenido', $contenido);       
      $sql->execute();            
      $fila_contenido = $sql->fetch(PDO::FETCH_ASSOC);            
      cursoCompleto($db, $fila_contenido['id_curso'], $usuario);      
    }

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
    
    //Consulta descripciones de contenido
    $sql = $dbConn->prepare("SELECT * FROM contenido_curso where id_contenido = :id_contenido");
    $sql->bindValue(':id_contenido', $contenido);              
    $sql->execute();
    $contenido_curso = $sql->fetch(PDO::FETCH_ASSOC);
    //Consulta descripcion de curso
    $sql = $dbConn->prepare("SELECT * FROM cursos where id_curso = :id_curso");              
    $sql->bindValue(':id_curso', $contenido_curso['id_curso']);              
    $sql->execute();            
    $fila_curso = $sql->fetch(PDO::FETCH_ASSOC);     
    $evento = "Se modificó el contenido " . $contenido_curso['nombre_contenido'] . " del curso " . $fila_curso['nombre_curso'];
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
    $respuesta['mensaje']="El contenido_alumno NO existe.";
    echo json_encode(  $respuesta  );
    exit();
  }   
}

?>