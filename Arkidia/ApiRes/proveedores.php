<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
/////////////////////////////
// CONSULTA DE PROVEEDORES //
////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  if(isset($_GET['usuario']))
  {   $evento="";
      if (isset($_GET['id_proveedor'])) // Si el campo proveedor existe es una búsqueda puntual
      {
          //Mostrar un proveedor particular
        if(!empty($_GET['id_proveedor'])) // Si el campo no está vacío
        {
          try{
            $sql = $dbConn->prepare("SELECT * FROM proveedores where id_proveedor=:id_proveedor");
            $sql->bindValue(':id_proveedor', $_GET['id_proveedor']);
            $sql->execute();
            $fila_proveedor = $sql->fetch(PDO::FETCH_ASSOC);
            if(empty($sql)){
              $respuesta['resultado']="ERROR";
              $respuesta['mensaje']="El proveedor NO existe en la tabla.";
              echo json_encode(  $respuesta  );            
            }else{ 
              $evento = "Consulta al proveedor " . $fila_proveedor['nombre_proveedor'];
              header("HTTP/1.1 200 OK");
              echo json_encode(  $fila_proveedor );          
            }
          }catch (Exception $e){
            $e->getMessage();          
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']=$e;
            echo json_encode(  $respuesta  );      
          }
        }else{
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="Debe ingresar un valor para la identificación de proveedor.";
          echo json_encode(  $respuesta  );               
        }
      }else {
        //Mostrar lista de proveedores completa
        try{
          $evento = "Consulta todos los proveedores.";
          $sql = $dbConn->prepare("SELECT * FROM proveedores");
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
  }elseif(!isset($input['nombre_proveedor']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar nombre_proveedor por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['usuario']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el usuario por POST.";    
    echo json_encode($respuesta);
    exit();
  }else
  {
    if(empty($input['nombre_proveedor']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el nombre del proveedor.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el usuario.";    
      echo json_encode($respuesta);
      exit();
    }else
    {
      //Busca la descripción en la tabla
      $consulta = $dbConn->prepare("SELECT * FROM proveedores where nombre_proveedor=:nombre_proveedor");
      $consulta->bindValue(':nombre_proveedor', $input['nombre_proveedor']);      
      $consulta->execute();
      $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
      if(!empty($fila_consulta['nombre_proveedor']))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="El proveedor " . $fila_consulta['nombre_proveedor'] . " ya existe en la tabla.";    
        echo json_encode($respuesta);
        exit();
      }else
      {
        try{
          $sql = "INSERT INTO proveedores
                (nombre_proveedor)
                VALUES
                (:nombre_proveedor)";          
          $statement = $dbConn->prepare($sql);          
          $statement->bindParam(':nombre_proveedor', $input['nombre_proveedor']);          
          $statement->execute();
        }catch(Exception $e)
        {
          $e->getMessage();          
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="$e";
          echo json_encode(  $respuesta  ); 
        }      
        $id_proveedor = $dbConn->lastInsertId();

        if($id_proveedor)
        {
          //Agrega el registro en el log de eventos
          $evento = "ALTA DE PROVEEDOR ".$input['nombre_proveedor'];    
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

///////////////////////////
// ELIMINAR UN PROVEEDOR //
//////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    if(!isset($_GET['id_proveedor']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_proveedor por POST.";
      echo json_encode(  $respuesta  );
      exit();     
    }elseif(!isset($_GET['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario por POST.";
      echo json_encode(  $respuesta  );
      exit();        
    }elseif(empty($_GET['id_proveedor']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_proveedor para eliminar.";
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
        $id_proveedor = $_GET['id_proveedor'];        
        //busca la descripción en la tabla
        $consulta = $dbConn->prepare("SELECT * FROM proveedores WHERE id_proveedor=:id_proveedor");
        $consulta->bindValue(':id_proveedor', $id_proveedor);        
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!empty($fila_consulta)) 
        {
          $evento = "ELIMINO EL PROVEEDOR ".$fila_consulta['nombre_proveedor'];
          $statement = $dbConn->prepare("DELETE FROM proveedores WHERE id_proveedor=:id_proveedor");
          $statement->bindValue(':id_proveedor', $id_proveedor);          
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