<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);

////////////////////////////
// ALTA DE LIKE CHALLENGE //
///////////////////////////
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
    $respuesta['mensaje']="Debe enviar usuario_challenge por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['usuario_like']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar usuario_like por POST.";    
    echo json_encode($respuesta);
    exit();
  }else
  {
    if(empty($input['id_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar id_challenge.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['usuario_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el usuario_challenge.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['usuario_like']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el usuario_like.";    
      echo json_encode($respuesta);
      exit(); 
    }else
    {
      //Buscar valor del puntaje que corresponde al like
      $puntaje = $dbConn->prepare("SELECT * FROM puntaje where evento='like'");
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
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha_formateada = date("Y-m-d H:i:s",time());
        try{

          $like = $dbConn->prepare("SELECT * FROM like_challenge 
                                    where id_challenge=:id_challenge 
                                    and usuario_challenge=:usuario_challenge and usuario_like=:usuario_like");
          $like->bindValue('id_challenge', $input['id_challenge']);
          $like->bindValue('usuario_challenge', $input['usuario_challenge']);
          $like->bindValue('usuario_like', $input['usuario_like']);         
          $like->execute();
          $fila_like = $like->fetch(PDO::FETCH_ASSOC);      
          if(!empty($fila_like))
          {
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']= $input['id_challenge'] . " ya tiene un like del usuario_like " . 
                                    $input['usuario_like'];
            echo json_encode($respuesta);
            exit();
          }
          //Insertar en tabla de likes_challenge para que quede registro de la acción del usuario
          $sql = "INSERT INTO like_challenge
                (id_challenge, usuario_challenge, usuario_like, fecha)
                VALUES
                (:id_challenge, :usuario_challenge, :usuario_like, :fecha)";          
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':id_challenge', $input['id_challenge']);
          $statement->bindParam(':usuario_challenge', $input['usuario_challenge']);
          $statement->bindParam(':usuario_like', $input['usuario_like']);
          $statement->bindParam(':fecha', $fecha_formateada);      
          $statement->execute();
          //Sumar 1 a total_likes  de challenge_alumno
          $challenge_alumno = $dbConn->prepare("SELECT * FROM challenge_alumno 
                                                  where id_challenge=:id_challenge and usuario = :usuario");
          $challenge_alumno->bindParam(':id_challenge', $input['id_challenge']);
          $challenge_alumno->bindParam(':usuario', $input['usuario_challenge']);
          $challenge_alumno->execute();
          $fila_challenge = $challenge_alumno->fetch(PDO::FETCH_ASSOC);
          $data=[     
            'id_challenge' => $input['id_challenge'],
            'usuario'      => $input['usuario_challenge'],
            'total_likes' => intval($fila_challenge['total_likes']) + 1,
          ];

          $sql = "UPDATE challenge_alumno
          SET total_likes = :total_likes
          WHERE id_challenge = :id_challenge and usuario = :usuario";
          $challenge_alumno = $dbConn->prepare($sql);     
          $challenge_alumno->execute($data);

          //Actualizar el puntaje del alumno
          $puntaje_alumno = $dbConn->prepare("SELECT * FROM puntaje_alumno where usuario=:usuario");
          $puntaje_alumno->bindParam(':usuario', $input['usuario_like']);
          $puntaje_alumno->execute();
          $fila_puntaje = $puntaje_alumno->fetch(PDO::FETCH_ASSOC);
          $puntos+=$fila_puntaje['puntaje'];          
          $data=[     
            'usuario' => $input['usuario_like'],
            'puntaje' => $puntos,
          ];        
          $sql = "UPDATE puntaje_alumno
          SET puntaje = :puntaje
          WHERE usuario = :usuario";
          $statement = $dbConn->prepare($sql);     
          $statement->execute($data);
          //Insertar en la tabla de notificaciones: a usuario_like le gustó tu foto
          $data=[     
            'id_challenge'      => $input['id_challenge'],
            'secuencia'         => 1,
            'usuario'           => $input['usuario_challenge'],
            'usuario_origen'    => $input['usuario_like'],
            'tipo_notificacion' => "like",
            'texto'             => "A " . $input['usuario_like'] . " le gustó tu foto.",
            'indicador_visto'   => "N",
            'fecha'             => $fecha_formateada,
          ];        
          $sql = "INSERT INTO notificaciones
                (id_challenge, secuencia, usuario, usuario_origen, tipo_notificacion, texto, indicador_visto, fecha)
                VALUES
                (:id_challenge, :secuencia, :usuario, :usuario_origen, :tipo_notificacion, :texto, :indicador_visto, :fecha)";
          $notificacion = $dbConn->prepare($sql);     
          $notificacion->execute($data);          

          header("HTTP/1.1 200 OK");
          $respuesta['resultado']="OK";
          $respuesta['mensaje']="";
          echo json_encode($respuesta);
          exit();          
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="$e";
          echo json_encode(  $respuesta  );
          exit();
        } 
      }      
    }   
  }
}


/////////////////////////////
// ELIMINAR LIKE CHALLENGE //
////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    if(!isset($_GET['id_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_challenge por POST.";
      echo json_encode(  $respuesta  );
      exit();      
    }elseif(!isset($_GET['usuario_like']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario_like por POST.";
      echo json_encode(  $respuesta  );
      exit();
    }elseif(!isset($_GET['usuario_challenge']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario_challenge por POST.";
      echo json_encode(  $respuesta  );
      exit();      
    }elseif(!isset($_GET['secuencia']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar la secuencia por POST.";
      echo json_encode(  $respuesta  );
      exit();         
    }else
    {        
      try{        
        //busca que exista en la tabla
        $consulta = $dbConn->prepare("SELECT * FROM like_challenge 
                                        where id_challenge=:id_challenge and usuario_like=:usuario_like
                                        and usuario_challenge = :usuario_challenge");
        $consulta->bindValue(':id_challenge', $_GET['id_challenge']);
        $consulta->bindValue(':usuario_like', $_GET['usuario_like']);
        $consulta->bindValue(':usuario_challenge', $_GET['usuario_challenge']);     
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
        $usuario_challenge= $fila_consulta['usuario_challenge']; 
        if (!empty($fila_consulta)) 
        {
          //Eliminar registro en tabla de likes_challenge
          $statement = $dbConn->prepare("DELETE FROM like_challenge 
                                          where id_challenge=:id_challenge and usuario_like=:usuario_like
                                          and usuario_challenge = :usuario_challenge");
          $statement->bindValue(':id_challenge', $_GET['id_challenge']);
          $statement->bindValue(':usuario_like', $_GET['usuario_like']);
          $statement->bindValue(':usuario_challenge', $_GET['usuario_challenge']);
          $statement->execute();
          //Restar 1 de total_likes en la tabla de challenge_alumno          
          $challenge_alumno = $dbConn->prepare("SELECT * FROM challenge_alumno 
                                                  where id_challenge=:id_challenge and usuario = :usuario");
          $challenge_alumno->bindParam(':id_challenge', $_GET['id_challenge']);
          $challenge_alumno->bindParam(':usuario', $_GET['usuario_challenge']);
          $challenge_alumno->execute();
          $fila_challenge = $challenge_alumno->fetch(PDO::FETCH_ASSOC);
          
          $data=[     
            'id_challenge' => $_GET['id_challenge'],
            'total_likes'  => $fila_challenge['total_likes'] - 1,
            'usuario'      => $_GET['usuario_challenge'],
          ];        
          $sql = "UPDATE challenge_alumno
          SET total_likes = :total_likes
          WHERE id_challenge = :id_challenge and usuario = :usuario";
          $challenge_alumno = $dbConn->prepare($sql);     
          $challenge_alumno->execute($data);
          //Buscar valor del puntaje que corresponde al like
          $puntaje = $dbConn->prepare("SELECT * FROM puntaje where evento='like'");
          $puntaje->execute();
          $fila_puntaje = $puntaje->fetch(PDO::FETCH_ASSOC);
          $valor=$fila_puntaje['puntaje'];
          //Busca el puntaje del alumno
          $puntaje_alumno = $dbConn->prepare("SELECT * FROM puntaje_alumno 
                  where usuario=:usuario");
          $puntaje_alumno->bindParam(':usuario', $_GET['usuario_like']);          
          $puntaje_alumno->execute();
          $fila_puntaje = $puntaje_alumno->fetch(PDO::FETCH_ASSOC);
          //Restar puntaje de puntaje alumno
          $puntos=$fila_puntaje['puntaje'] - $valor;          
          $data=[     
            'usuario' => $_GET['usuario_like'],
            'puntaje' => $puntos,
          ];        
          $sql = "UPDATE puntaje_alumno
          SET puntaje = :puntaje
          WHERE usuario = :usuario";
          $statement = $dbConn->prepare($sql);     
          $statement->execute($data);
          //Eliminar registro en tabla de notificaciones
          $statement = $dbConn->prepare("DELETE FROM notificaciones 
                                          where id_challenge=:id_challenge and usuario=:usuario
                                          and secuencia=:secuencia");
          $statement->bindValue(':id_challenge', $_GET['id_challenge']);
          $statement->bindValue(':usuario', $usuario_challenge);
          $statement->bindValue(':secuencia', $_GET['secuencia']);
          $statement->execute();

          $respuesta['resultado']="OK";
          $respuesta['mensaje']="";    
          echo json_encode($respuesta);
          header("HTTP/1.1 200 OK");
          exit();
        }else
        {
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="El no existe en la tabla.";    
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


////////////////////////////////////
//  CONSULTA DE TABLA LIKE CURSO  //
/////////////////////////////////// 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if(isset($_GET['id_challenge']) || empty($_GET['id_challenge']))
  {
    if(!isset($_GET['usuario_challenge']) || empty($_GET['usuario_challenge']))
    {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="Debe ingresar usuario_challenge.";
        echo json_encode(  $respuesta  );
        exit();    
    }  
    try{
      $sql = $dbConn->prepare("SELECT * FROM like_challenge 
                                where id_challenge = :id_challenge and usuario_challenge=:usuario_challenge");
      $sql->bindValue(':id_challenge', $_GET['id_challenge']);
      $sql->bindValue('usuario_challenge', $_GET['usuario_challenge']);
      $sql->execute();
      $dato = $sql->fetchAll();      
      if(!empty($dato)){
        $respuesta = array();
        foreach($dato as $row)
        {
          $hijo = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario=:usuario");          
          $hijo->bindValue(':usuario', $row['usuario_like']);      
          $hijo->execute();
          $fila_hijo = $hijo->fetch(PDO::FETCH_ASSOC);
          if($fila_hijo['usuario']==""){
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']="El usuario NO existe en la tabla.";
            echo json_encode(  $respuesta  );
            exit();
          }          
          $item = array(
          'usuario'  => $row['usuario_like'],
          'alias'    => $fila_hijo['alias']                               
          );
          array_push($respuesta, $item);
        }        
        header("HTTP/1.1 200 OK");
        echo json_encode(  $respuesta  );
        exit();
      }else
      {
        $respuesta = array();
        echo json_encode(  $respuesta  );
        exit();             
      }
    }catch (Exception $e){
      $e->getMessage();          
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']=$e;
      echo json_encode(  $respuesta  );
      exit();    
    }    
  }else{
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el challenge.";
      echo json_encode(  $respuesta  );
      exit(); 
  }
}


//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>