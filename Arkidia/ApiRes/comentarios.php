<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
  /////////////////////////
  // ALTA DE COMENTARIOS //
  ////////////////////////
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
    }elseif(!isset($input['id_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar id_challenge por POST.";    
      echo json_encode($respuesta);
      exit();
    }elseif(!isset($input['usuario_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el campo usuario_challenge por POST.";    
      echo json_encode($respuesta);
      exit();
    }elseif(!isset($input['usuario_comentario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el campo usuario_comentario por POST.";    
      echo json_encode($respuesta);
      exit();
    }elseif(!isset($input['comentario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el campo comentario por POST.";    
      echo json_encode($respuesta);
      exit();    
    }else
    {
      if(empty($input['id_challenge']))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="Debe completar id_challenge del curso.";    
        echo json_encode($respuesta);
        exit();
      }elseif(empty($input['usuario_challenge']))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="Debe completar el usuario que dio de alta el challenge.";    
        echo json_encode($respuesta);
        exit();
      }elseif(empty($input['usuario_comentario']))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="Debe indicar el usuario que hace el comentario en el challenge.";    
        echo json_encode($respuesta);
        exit();
      }else
      {
        $secuencia=1;
        //Busca la última secuencia de comentario del challenge
        $consulta = $dbConn->prepare("SELECT * FROM comentarios 
                where id_challenge=:id_challenge ORDER BY secuencia DESC LIMIT 1");
        $consulta->bindValue(':id_challenge', $input['id_challenge']);
        $consulta->execute();
        $ultima_secuencia = $consulta->fetch(PDO::FETCH_ASSOC);
        if(!empty($ultima_secuencia))
        {
          $secuencia = $ultima_secuencia['secuencia'] + 1;
        }
        else
        { 
          $secuencia = 1;
        }
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha_formateada = date("Y-m-d H:i:s",time());
        try{
          //Inserta el comentario en la tabla comentario
          $sql = "INSERT INTO comentarios
                (id_challenge, secuencia, usuario_challenge, usuario_comentario, comentario, fecha, estado, revisor)
                VALUES
                (:id_challenge, :secuencia, :usuario_challenge, :usuario_comentario, :comentario, :fecha, :estado, :revisor)";
          $estado = "B";
          $espacios="";
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':id_challenge', $input['id_challenge']);          
          $statement->bindParam(':secuencia', $secuencia);
          $statement->bindParam(':usuario_challenge', $input['usuario_challenge']);   
          $statement->bindParam(':usuario_comentario', $input['usuario_comentario']);
          $statement->bindParam(':comentario', $input['comentario']);
          $statement->bindParam(':fecha', $fecha_formateada);
          $statement->bindParam(':estado', $estado);
          $statement->bindParam(':revisor', $espacios);
          $statement->execute();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']=$e;
          echo json_encode(  $respuesta  ); 
        }

        //Agrega el registro en el log de eventos
        $consulta = $dbConn->prepare("SELECT * FROM challenges_cursos where id_challenge=:id_challenge");
        $consulta->bindValue(':id_challenge', $input['id_challenge']);
        $consulta->execute();
        $challenge = $consulta->fetch(PDO::FETCH_ASSOC);        
        $evento = "Realizó un comentario sobre el challenge ".$challenge['nombre_challenge'];    
  
        //Graba registro en tabla Log
       try{
          $sql = "INSERT INTO log 
                (fecha, evento, usuario)
                VALUES
                (:fecha, :evento, :usuario)";
          $statement = $dbConn->prepare($sql);  
          $statement->bindParam(':fecha', $fecha_formateada);
          $statement->bindParam(':evento', $evento);
          $statement->bindParam(':usuario', $input['usuario_comentario']);          
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
        $respuesta['comentario'] = $secuencia;
        echo json_encode($respuesta);
        exit();
                
      }
    }    
  }
///////////////////////////////
//  CONSULTA DE COMENTARIOS  // 
//////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  $evento="";
  $input= $_GET;
  if (isset($input['id_challenge']) and isset($input['usuario_challenge']) 
  and isset($input['usuario']) and !isset($input['estado'])) // 
  {
    //Mostrar un curso particular
    if(empty($input['id_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar id_challenge del curso.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['usuario_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el usuario que dio de alta el challenge.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el usuario logueado.";    
      echo json_encode($respuesta);
      exit();        
    }else
    {
      try{
        //Lista todos los comentarios de un id_challenge y un usuario determinado
        $sql = $dbConn->prepare("SELECT * FROM comentarios 
                                WHERE id_challenge = :id_challenge AND usuario_challenge=:usuario_challenge 
                                AND (estado = 'P' OR (estado = 'B' AND usuario_comentario = :usuario_comentario)) ORDER BY secuencia");
        $sql->bindValue(':id_challenge', $input['id_challenge']);        
        $sql->bindValue(':usuario_challenge', $input['usuario_challenge']);
        $sql->bindValue(':usuario_comentario', $input['usuario']);
        $sql->execute();   
        $fila = $sql->fetchAll();
        
        if(empty($fila)){
          //Si no hay comentarios
          header("HTTP/1.1 200 OK");
          $respuesta=array();          
          echo json_encode(  $respuesta  );
          exit();           
        }else{

          
          $lista_comentarios=array();
          foreach($fila as $row)
          {  
            //Consulta datos del usuario que hizo los comentarios
            $sql = $dbConn->prepare("SELECT * FROM usuario_hijo 
                                    where usuario=:usuario");
            $sql->bindValue(':usuario', $row['usuario_comentario']);
            $sql->execute();
            $usuario_comentario = $sql->fetch(PDO::FETCH_ASSOC);
            if($row['usuario_comentario'] == $input['usuario'])
            {
              $ind_comentario = "1";
            }
            else
            {
              $ind_comentario = "0";
            }

            $item = array(          
              'usuario_comentario' => $row['usuario_comentario'],
              //'id_challenge'       =>$row
              'alias'              => $usuario_comentario['alias'],
              'avatar'             => $usuario_comentario['avatar'],
              'fechahora'          => $row['fecha'],
              'comentario'         => $row['comentario'],
              'ind_comentario'     => $ind_comentario,
              'secuencia'          => $row['secuencia'] 
            );
            array_push($lista_comentarios, $item);
            $ind_comentario ="0";
          }

          header("HTTP/1.1 200 OK");
          echo json_encode(  $lista_comentarios  );
          //echo json_encode($respuesta);
          exit();
        }
      }catch (Exception $e)
      {
        $e->getMessage();          
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']=$e;
        echo json_encode(  $respuesta  );
        exit();      
      }
    }
  ////////////////////////////////////////////////////////////////  
  }elseif (isset($input['usuario']) and isset($input['estado'])) 
  {
    //Esto se obtiene desde el módulo de Administrador
    if(empty($input['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar usuario del curso.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['estado']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el estado del comentario.";    
      echo json_encode($respuesta);
      exit();      
    }else
    {
      try{
        //Lista todos los comentarios 
        $sql = $dbConn->prepare("SELECT * FROM comentarios 
                                WHERE estado = :estado ORDER BY secuencia");
        $sql->bindValue(':estado', $input['estado']);

        $sql->execute();   
        $fila = $sql->fetchAll();
        
        if(empty($fila)){
          //Si no hay comentarios
          header("HTTP/1.1 200 OK");
          $respuesta=array();
          echo json_encode(  $respuesta  );
          exit();           
        }else{          
          $lista_comentarios=array();
          foreach($fila as $row)
          {  
            //Consulta datos del usuario que hizo los comentarios
            $sql = $dbConn->prepare("SELECT * FROM usuario_hijo 
                                    where usuario=:usuario");
            $sql->bindValue(':usuario', $row['usuario_comentario']);
            $sql->execute();
            $usuario_comentario = $sql->fetch(PDO::FETCH_ASSOC);

            $item = array(          
              'usuario_comentario' => $row['usuario_comentario'],
              'usuario_challenge'  => $row['usuario_challenge'],
              'id_challenge'       => $row['id_challenge'],
              'alias'              => $usuario_comentario['alias'],
              'avatar'             => $usuario_comentario['avatar'],
              'fechahora'          => $row['fecha'],
              'comentario'         => $row['comentario'],
              'ind_comentario'     => "0",
              'secuencia'          => $row['secuencia'] 
            );
            array_push($lista_comentarios, $item);
            $ind_comentario ="0";
          }

          header("HTTP/1.1 200 OK");
          echo json_encode(  $lista_comentarios  );
          //echo json_encode($respuesta);
          exit();
        }
      }catch (Exception $e)
      {
        $e->getMessage();          
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']=$e;
        echo json_encode(  $respuesta  );
        exit();      
      }
    }
  }
}


////////////////////////////
// MODIFICACION DE DATOS //
///////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
  $input = $_GET;
  if(!isset($input['id_challenge']) AND !isset($input['usuario_challenge']) AND !isset($input['secuencia'])
      AND !isset($input['estado']) AND !isset($input['usuario']))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar por POST los campos id_challenge, usuario_challenge, secuencia y estado.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(empty($input['id_challenge']) AND empty($input['usuario_challenge']) AND empty($input['secuencia'])
      AND empty($input['estado']) AND empty($input['usuario']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe completar todos los datos que ingresa como parámetro.";
    echo json_encode(  $respuesta  );
    exit();
  }elseif($input['estado'] != "P" && $input['estado'] != "R" && $input['estado'] != "B")
  {
    $respuesta['resultado'] = "ERROR";
    $respuesta['mensaje'] = "El estado debe ser P (publicado), E (eliminado), R (rechazado) o B (borrador)";
    echo json_encode($respuesta);
    exit();
  }else
  {    
    
    $consulta = $dbConn->prepare("SELECT * FROM comentarios 
          where id_challenge = :id_challenge AND usuario_challenge = :usuario_challenge
          AND secuencia = :secuencia");
    $consulta->bindValue(':id_challenge', $input['id_challenge']);
    $consulta->bindValue(':usuario_challenge', $input['usuario_challenge']);
    $consulta->bindValue(':secuencia', $input['secuencia']);
    $consulta->execute();
    $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);        

  } 
  if(!empty($fila_consulta))
  {
    try{
 
      $data=[
        'id_challenge'=> $input['id_challenge'],
        'usuario_challenge' => $input['usuario_challenge'],
        'secuencia' => $input['secuencia'],
        'estado' => $input['estado'],
        'revisor' => $input['usuario'],
      ];
      $sql = "UPDATE comentarios 
      SET estado = :estado, revisor =:revisor
      WHERE id_challenge = :id_challenge AND usuario_challenge = :usuario_challenge AND secuencia = :secuencia";
      $statement = $dbConn->prepare($sql);     
      $statement->execute($data);
      if($input['estado'] == "P")
      {
        try{
          //Sumar 1 a total_comentarios  de challenge_alumno
          $challenge_alumno = $dbConn->prepare("SELECT * FROM challenge_alumno 
                                                  where id_challenge=:id_challenge and usuario = :usuario");
          $challenge_alumno->bindParam(':id_challenge', $input['id_challenge']);
          $challenge_alumno->bindParam(':usuario', $input['usuario_challenge']);
          $challenge_alumno->execute();
          $fila_challenge = $challenge_alumno->fetch(PDO::FETCH_ASSOC);
          $data=[     
            'id_challenge' => $input['id_challenge'],
            'usuario'      => $input['usuario_challenge'],
            'total_comentarios' => intval($fila_challenge['total_comentarios']) + 1,
          ];

          $sql = "UPDATE challenge_alumno
          SET total_comentarios = :total_comentarios
          WHERE id_challenge = :id_challenge and usuario = :usuario";
          $challenge_alumno = $dbConn->prepare($sql);     
          $challenge_alumno->execute($data);          
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']=$e;
          echo json_encode(  $respuesta  );
          exit();
        }
        try{
          //Buscar valor del puntaje que corresponde al comentario
          $puntaje = $dbConn->prepare("SELECT * FROM puntaje where evento='comentario'");
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
          }         
          //Actualizar el puntaje del alumno
          $puntaje_alumno = $dbConn->prepare("SELECT * FROM puntaje_alumno where usuario=:usuario");
          $puntaje_alumno->bindParam(':usuario', $fila_consulta['usuario_comentario']);
          $puntaje_alumno->execute();
          $fila_puntaje = $puntaje_alumno->fetch(PDO::FETCH_ASSOC);
          $puntos+=$fila_puntaje['puntaje'];          
          $data=[     
            'usuario' => $fila_consulta['usuario_comentario'],
            'puntaje' => $puntos,
          ];        
          $sql = "UPDATE puntaje_alumno
          SET puntaje = :puntaje
          WHERE usuario = :usuario";
          $statement = $dbConn->prepare($sql);     
          $statement->execute($data);          
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']=$e;
          echo json_encode(  $respuesta  ); 
        }

        
        //Busca la última secuencia de notificación del usuario_challenge + id_challenge + tipo_comentario
        $tipo = "comentario";
        $consulta = $dbConn->prepare("SELECT * FROM notificaciones 
                where id_challenge = :id_challenge and usuario = :usuario
                and tipo_notificacion = :tipo_notificacion            
                ORDER BY secuencia DESC LIMIT 1");
        $consulta->bindValue(':id_challenge', $input['id_challenge']);
        $consulta->bindValue(':usuario', $input['usuario_challenge']);
        $consulta->bindValue(':tipo_notificacion', $tipo);
        $consulta->execute();
        $ultima_secuencia = $consulta->fetch(PDO::FETCH_ASSOC);
        if(!empty($ultima_secuencia))
        {
          $secuencia = $ultima_secuencia['secuencia'] + 1;
        }
        else
        { 
          $secuencia = 1;
        }        
        //Inserta la notificación para que la vea el usuario que subió el challenge
        try{
          //Inserta el comentario en la tabla comentario
          $sql = "INSERT INTO notificaciones
                (id_challenge, secuencia, usuario, usuario_origen, tipo_notificacion, texto, indicador_visto, fecha)
                VALUES
                (:id_challenge, :secuencia, :usuario, :usuario_origen, :tipo_notificacion, :texto, :indicador_visto, :fecha)";
          $indicador_visto = "N";          
          $espacios="";
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':id_challenge', $fila_consulta['id_challenge']);          
          $statement->bindParam(':secuencia', $secuencia);
          $statement->bindParam(':usuario', $fila_consulta['usuario_challenge']);   
          $statement->bindParam(':usuario_origen', $fila_consulta['usuario_comentario']);
          $statement->bindParam(':tipo_notificacion', $tipo);
          $statement->bindParam(':texto', $fila_consulta['comentario']);
          $statement->bindParam(':indicador_visto', $indicador_visto);
          $statement->bindParam(':fecha', $fila_consulta['fecha']);
          $statement->execute();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']= "Notificaciones " . $e;
          echo json_encode(  $respuesta  ); 
        }
        $evento = "Aprobado el comentario " . $input['id_challenge'] . " de " . $input['usuario_challenge'] .
            " secuencia " . $input['secuencia'];      
      }elseif($input['estado'] == "R")
      {
        $evento = "Rechazado el comentario " . $input['id_challenge'] . " de " . $input['usuario_challenge'] .
            " secuencia " . $input['secuencia'];
      
      }elseif($input['estado'] == "B")
      {
        $evento = "Pasó a borrador el comentario " . $input['id_challenge'] . " de " . $input['usuario_challenge'] .
            " secuencia " . $input['secuencia'];      
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
      $respuesta['mensaje']= $e;
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
    $respuesta['mensaje']="El comentario NO existe.";
    echo json_encode(  $respuesta  );
    exit();
  }   
}
///////////////////////////////////
// ELIMINAR COMENTARIO CHALLENGE //
//////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    $input = $_GET;
    if(!isset($input['id_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_challenge por POST.";
      echo json_encode(  $respuesta  );
      exit();      
    }elseif(!isset($input['usuario_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario_challenge por POST.";
      echo json_encode(  $respuesta  );
      exit();
    }elseif(!isset($input['secuencia']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar la secuencia por POST.";
      echo json_encode(  $respuesta  );
      exit();      
    }elseif(!isset($input['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario por POST.";
      echo json_encode(  $respuesta  );
      exit();         
    }else
    {        
      try{        
        //busca que exista en la tabla de comentarios
        $consulta = $dbConn->prepare("SELECT * FROM comentarios 
                                        where id_challenge=:id_challenge 
                                        and usuario_challenge = :usuario_challenge
                                        and secuencia = :secuencia");
        $consulta->bindValue(':id_challenge', $input['id_challenge']);        
        $consulta->bindValue(':usuario_challenge', $input['usuario_challenge']);  
        $consulta->bindValue(':secuencia', $input['secuencia']);   
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
        $usuario_challenge= $fila_consulta['usuario_challenge']; 
        $estado_actual=$fila_consulta['estado'];
        if (!empty($fila_consulta)) 
        {
          //Pone la marca de Eliminar (E) en el registro en tabla de comentarios
          $data=[
            'id_challenge'=> $input['id_challenge'],
            'usuario_challenge' => $input['usuario_challenge'],
            'secuencia' => $input['secuencia'],
            'estado' => "E",
          ];
          $sql = "UPDATE comentarios 
          SET estado = :estado
          WHERE id_challenge = :id_challenge AND usuario_challenge = :usuario_challenge 
                AND secuencia = :secuencia";
          $statement = $dbConn->prepare($sql);     
          $statement->execute($data);
          //Restar 1 de total_comentarios en la tabla de challenge_alumno          
          $challenge_alumno = $dbConn->prepare("SELECT * FROM challenge_alumno 
                                                  where id_challenge=:id_challenge and usuario = :usuario");
          $challenge_alumno->bindParam(':id_challenge', $input['id_challenge']);
          $challenge_alumno->bindParam(':usuario', $input['usuario_challenge']);
          $challenge_alumno->execute();
          $fila_challenge = $challenge_alumno->fetch(PDO::FETCH_ASSOC);
          if($estado_actual == "P")
          {
            $data=[     
              'id_challenge' => $_GET['id_challenge'],
              'total_comentarios'  => $fila_challenge['total_comentarios'] - 1,
              'usuario'      => $_GET['usuario_challenge'],
            ];        
            $sql = "UPDATE challenge_alumno
            SET total_comentarios = :total_comentarios
            WHERE id_challenge = :id_challenge and usuario = :usuario";
            $challenge_alumno = $dbConn->prepare($sql);     
            $challenge_alumno->execute($data);

            //Buscar valor del puntaje que corresponde al comentario
            $puntaje = $dbConn->prepare("SELECT * FROM puntaje where evento='comentario'");
            $puntaje->execute();
            $fila_puntaje = $puntaje->fetch(PDO::FETCH_ASSOC);
            $valor=$fila_puntaje['puntaje'];
            //Busca el puntaje del alumno
            $puntaje_alumno = $dbConn->prepare("SELECT * FROM puntaje_alumno 
                    where usuario=:usuario");
            $puntaje_alumno->bindParam(':usuario', $input['usuario']);          
            $puntaje_alumno->execute();
            $fila_puntaje = $puntaje_alumno->fetch(PDO::FETCH_ASSOC);
            //Restar puntaje de puntaje alumno
            $puntos=$fila_puntaje['puntaje'] - $valor;          
            $data=[     
              'usuario' => $input['usuario'],
              'puntaje' => $puntos,
            ];        
            $sql = "UPDATE puntaje_alumno
            SET puntaje = :puntaje
            WHERE usuario = :usuario";
            $statement = $dbConn->prepare($sql);     
            $statement->execute($data);
            $respuesta['resultado']="OK";
            $respuesta['mensaje']="";
          }    
          echo json_encode($respuesta);
          header("HTTP/1.1 200 OK");
          exit();
        }else
        {
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="El comentario que busca NO existe en la tabla.";    
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


//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
