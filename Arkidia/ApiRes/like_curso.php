<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);

/////////////////////////
// ALTA DE LIKE CURSO //
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
  }elseif(!isset($input['usuario']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar usuario por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['id_curso']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar id_curso por POST.";    
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
    }elseif(empty($input['id_curso']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el id_curso.";    
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
        $respuesta['mensaje']="El evento " . $fila_puntaje['evento'] . " no existe en la tabla.";    
        echo json_encode($respuesta);
        exit();
      }else
      {
        $puntos= intval($fila_puntaje['puntaje']);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha_formateada = date("Y-m-d H:i:s",time());
        try{

          $like = $dbConn->prepare("SELECT * FROM like_curso 
                                      where id_curso=:id_curso and usuario_like=:usuario");
          $like->bindValue('id_curso', $input['id_curso']);
          $like->bindValue('usuario', $input['usuario']);
          $like->execute();
          $fila_like = $like->fetch(PDO::FETCH_ASSOC);      
          if(!empty($fila_like))
          {
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']= $input['usuario'] . " ya tiene un like en el curso " . $input['id_curso'];
            echo json_encode($respuesta);
            exit();
          }
          //Insertar en tabla de likes_curso para que quede registro de la acción del usuario
          $sql = "INSERT INTO like_curso
                (id_curso, usuario_like, fecha)
                VALUES
                (:id_curso, :usuario_like, :fecha)";          
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':id_curso', $input['id_curso']);
          $statement->bindParam(':usuario_like', $input['usuario']);
          $statement->bindParam(':fecha', $fecha_formateada);      
          $statement->execute();
          //Actualizar el puntaje del alumno
          $puntaje_alumno = $dbConn->prepare("SELECT * FROM puntaje_alumno where usuario=:usuario");
          $puntaje_alumno->bindParam(':usuario', $input['usuario']);
          $puntaje_alumno->execute();
          $fila_puntaje = $puntaje_alumno->fetch(PDO::FETCH_ASSOC);
          $puntos+=intval($fila_puntaje['puntaje']);          
          $data=[     
            'usuario' => $input['usuario'],
            'puntaje' => $puntos,
          ];        
          $sql = "UPDATE puntaje_alumno
          SET puntaje = :puntaje
          WHERE usuario = :usuario";
          $statement = $dbConn->prepare($sql);     
          $statement->execute($data);
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
        } 
      }      
    }   
  }
}

////////////////////////////////////
//  CONSULTA DE TABLA LIKE CURSO  //
/////////////////////////////////// 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if(isset($_GET['id_curso']) || empty($_GET['id_curso']))
  {    
    try{
      $sql = $dbConn->prepare("SELECT * FROM like_curso where id_curso = :id_curso");
      $sql->bindValue(':id_curso', $_GET['id_curso']);
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
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="No existe el usuario en la tabla.";
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
      $respuesta['mensaje']="Debe indicar el curso.";
      echo json_encode(  $respuesta  );
      exit(); 
  }
}

//////////////////////////
// ELIMINAR LIKE CURSO //
/////////////////////////
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
    }else
    {        
      try{        
        //busca que exista en la tabla
        $consulta = $dbConn->prepare("SELECT * FROM like_curso 
                                        where id_curso=:id_curso and usuario_like=:usuario");
        $consulta->bindValue(':id_curso', $_GET['id_curso']);
        $consulta->bindValue(':usuario', $_GET['usuario']);
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC); 
        if (!empty($fila_consulta)) 
        {
          //Eliminar registro en tabla de likes_curso
          $statement = $dbConn->prepare("DELETE FROM like_curso 
                                          where id_curso=:id_curso and usuario_like=:usuario");
          $statement->bindValue(':id_curso', $_GET['id_curso']);
          $statement->bindValue(':usuario', $_GET['usuario']);
          $statement->execute();
          //Buscar valor del puntaje que corresponde al like
          $puntaje = $dbConn->prepare("SELECT * FROM puntaje where evento='like'");
          $puntaje->execute();
          $fila_puntaje = $puntaje->fetch(PDO::FETCH_ASSOC);
          $valor=intval($fila_puntaje['puntaje']);
          //Busca el puntaje del alumno
          $puntaje_alumno = $dbConn->prepare("SELECT * FROM puntaje_alumno where usuario=:usuario");
          $puntaje_alumno->bindParam(':usuario', $_GET['usuario']);
          $puntaje_alumno->execute();
          $fila_puntaje = $puntaje_alumno->fetch(PDO::FETCH_ASSOC);
          //Actualizar el puntaje del alumno
          $puntos=intval($fila_puntaje['puntaje']) - $valor;          
          $data=[     
            'usuario' => $_GET['usuario'],
            'puntaje' => $puntos,
          ];        
          $sql = "UPDATE puntaje_alumno
          SET puntaje = :puntaje
          WHERE usuario = :usuario";
          $statement = $dbConn->prepare($sql);     
          $statement->execute($data);
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

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>