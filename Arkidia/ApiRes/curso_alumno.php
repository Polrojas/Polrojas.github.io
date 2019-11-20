<?php 
include "config.php";
include "utils.php";
$dbConn =  connect($db);
//////////////////////////////////////
//  CONSULTA DE TABLA CURSO_ALUMNO  //
///////////////////////////////////// 

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if(isset($_GET['usuario']))
  {   
      $evento="";
      if (isset($_GET['id_curso'])) // Si el campo categoría existe es una búsqueda puntual
      {
          //Mostrar una categoría particular
        if(!empty($_GET['id_curso'])) // Si el campo no está vacío
        {
          try{
            $sql = $dbConn->prepare("SELECT * from curso_alumno where id_curso = :id_curso and usuario = :usuario");
            $sql->bindValue(':id_curso', $_GET['id_curso']);
            $sql->bindValue(':usuario', $_GET['usuario']);
            $sql->execute();            
            $fila_alumno = $sql->fetch(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");          
            if($fila_alumno == false)
            {
              $respuesta['resultado']="OK";
              $respuesta['mensaje']=""; 
              $respuesta['inscripto']=false;
              $respuesta['ind_completo'] = false;
              echo json_encode($respuesta);
              exit();          
            }else{
              if($fila_alumno['ind_completo'] == 0)
              {
                $indicador=false;
              }else{
                $indicador=true;
              }
              //Busco nombre de curso
              $sql = $dbConn->prepare("SELECT * from cursos where id_curso = :id_curso");
              $sql->bindValue(':id_curso', $_GET['id_curso']);              
              $sql->execute();            
              $fila_alumno = $sql->fetch(PDO::FETCH_ASSOC);             
              
              $evento = "Consulta situación del curso " . $fila_alumno['nombre_curso'];
              header("HTTP/1.1 200 OK");
              $respuesta['resultado']="OK";
              $respuesta['mensaje']=""; 
              $respuesta['inscripto']=true;
              $respuesta['ind_completo'] = $indicador;
              echo json_encode($respuesta);                    
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
          $respuesta['mensaje']="Debe completar el id_curso";
          echo json_encode(  $respuesta  );
          exit();            
        }
      }else {

        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="Debe enviar por POST el id_curso.";
        echo json_encode(  $respuesta  );
        exit();
      }
      //Se registrará cuando exsita un evento ejecutado por un usuario
      if($evento != "") 
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
        exit();       
      }        
  }else{
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="Debe indicar el mail del administrador.";
        echo json_encode(  $respuesta  );
        exit();
  }
}
////////////////////////////
// ACTUALIZACION DE DATOS //
///////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
  $curso        = $_GET['id_curso'];
  $usuario      = $_GET['usuario'];
  $ind_completo = $_GET['ind_completo'];
  if(!isset($curso))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar por POST el id_curso.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(empty($curso))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el id_curso.";
    echo json_encode(  $respuesta  );
    exit();
  }else
  {
    $consulta = $dbConn->prepare("SELECT * FROM curso_alumno where id_curso = :id_curso and usuario = :usuario");
    $consulta->bindValue(':id_curso', $curso);
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
  }elseif(!isset($ind_completo))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar por POST el ind_completo.";
      echo json_encode(  $respuesta  );
      exit();    
  }elseif(empty($usuario))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el usuario.";
      echo json_encode(  $respuesta  );
      exit();
  }elseif(empty($ind_completo))
  {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el ind_completo.";
      echo json_encode(  $respuesta  );
      exit();    
  }elseif($fila_consulta)
  {
    try{
 
      $data=[
        'id_curso'=> $curso,
        'usuario'=>$usuario,
        'ind_completo' => $ind_completo,        
      ];
      $sql = "UPDATE curso_alumno 
      SET ind_completo = :ind_completo
      WHERE id_curso = :id_curso and usuario = :usuario";
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
    $evento = "MODIFICO EL CURSO ";
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
    $respuesta['mensaje']="El curso_alumno NO existe.";
    echo json_encode(  $respuesta  );
    exit();
  }   
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>