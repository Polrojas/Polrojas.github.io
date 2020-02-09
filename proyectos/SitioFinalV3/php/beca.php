<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);


///////////////////////
// ALTA DE PROVEEDOR //
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
  }elseif(!isset($input['nombre']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar nombre por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['mail']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el mail por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['telefono']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el telefono por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['ciudad']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el ciudad por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['pais']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el pais por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['especialidad']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el especialidad por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['matricula']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el matricula por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['beca']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el beca por POST.";    
    echo json_encode($respuesta);
    exit();
  }else
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $alta = date("Y-m-d H:i:s",time());    
        try{
          $sql = "INSERT INTO beca
                (nombre, mail, telefono, ciudad, pais, especialidad, matricula, beca, alta)
                VALUES
                (:nombre, :mail, :telefono, :ciudad, :pais, :especialidad, :matricula, :beca, :alta)";          
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':nombre', $input['nombre']); 
          $statement->bindParam(':mail', $input['mail']);    
          $statement->bindParam(':telefono', $input['telefono']);     
          $statement->bindParam(':ciudad', $input['ciudad']);  
          $statement->bindParam(':pais', $input['pais']);      
          $statement->bindParam(':especialidad', $input['especialidad']);   
          $statement->bindParam(':matricula', $input['matricula']);  
          $statement->bindParam(':beca', $input['beca']);    
          $statement->bindParam(':alta', $input['alta']);          
          $statement->execute();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']= "Error";
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

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>