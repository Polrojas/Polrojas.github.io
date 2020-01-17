<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);

/////////////////////////////////
//  CONSULTA DE TABLA NIVELES  //
///////////////////////////////// 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  $input= $_GET;
  if(isset($input['usuario']))
  {   
      $evento="";
      if (isset($_GET['nombre_nivel'])) // Si el campo evento existe es una búsqueda puntual
      {
          //Mostrar una evento particular
        if(!empty($_GET['nombre_nivel'])) // Si el campo no está vacío
        {
          try{
            $sql = $dbConn->prepare("SELECT * FROM niveles where nombre_nivel = :nombre_nivel");
            $sql->bindValue(':nombre_nivel', $_GET['nombre_nivel']);
            $sql->execute();
            $dato=$sql->fetch(PDO::FETCH_ASSOC);
            $evento = "Consulta el nivel ". $dato['nombre_nivel'] ;
            header("HTTP/1.1 200 OK");
            if(!empty($dato)){
               	echo json_encode(  $sql->fetchAll()  );
            }else
            {
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']="No existe el nivel en la tabla.";
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
          $respuesta['mensaje']="Debe completar el nivel de Arkidian";
          echo json_encode(  $respuesta  ); 
          exit();             
        }
      }else {
        if (!empty($input['usuario']))
        {
          try{
            $sql = $dbConn->prepare("SELECT * FROM niveles");           
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $evento = "Consulta todos los niveles existentes." ;
            header("HTTP/1.1 200 OK");
            echo json_encode(  $sql->fetchAll()  );
          }catch (Exception $e){
            $e->getMessage();          
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']=$e;
            echo json_encode(  $respuesta  );
            exit();     
          }
        }else
        {
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="Debe completar el usuario";
          echo json_encode(  $respuesta  ); 
          exit();             
        }
      }
      //Se registrará cuando exsita un evento ejecutado por un usuario
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
  }else{
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="Debe indicar el mail del administrador.";
        echo json_encode(  $respuesta  );
        exit(); 
  }
}


/////////////////////
// ALTA DE NIVELES //
////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $input = $_POST;

  if(empty($input))//Si no envia ningún campo por POST
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar los campos usuario, nombre_nivel y puntaje_maximo para el alta.";    
    echo json_encode($respuesta);
    exit();    
  //Chequeo que envíe todos los campos que necesita la API para hacer el insert
  }elseif(!isset($input['usuario']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar usuario por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['nombre_nivel']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar nombre_nivel por POST.";    
    echo json_encode($respuesta);
    exit();
  }elseif(!isset($input['puntaje_maximo']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el puntaje_maximo por POST.";    
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
    }elseif(empty($input['nombre_nivel']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe indicar el nombre del nivel.";    
      echo json_encode($respuesta);
      exit();
    }elseif(empty($input['puntaje_maximo']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe completar el puntaje maximo para el parámetro.";    
      echo json_encode($respuesta);
      exit();   
    }else
    {
      //Busca la descripción en la tabla
      $consulta = $dbConn->prepare("SELECT * FROM niveles where nombre_nivel=:nombre_nivel");
      $consulta->bindValue(':nombre_nivel', $input['nombre_nivel']);
      $consulta->execute();
      $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
      if(!empty($fila_consulta))
      {
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="El nivel " . $fila_consulta['nombre_nivel'] . " ya existe en la tabla.";    
        echo json_encode($respuesta);
        exit();
      }else
      {
      try{
        $sql = "INSERT INTO niveles
              (nombre_nivel, puntaje_maximo)
              VALUES
              (:nombre_nivel, :puntaje_maximo)";          
        $statement = $dbConn->prepare($sql);          
        $statement->bindParam(':nombre_nivel', $input['nombre_nivel']);
        $statement->bindParam(':puntaje_maximo', $input['puntaje_maximo']);          
        $statement->execute();
      }catch(Exception $e)
      {
        $e->getMessage();          
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="$e";
        echo json_encode(  $respuesta  );
        exit();
      }    
      //Agrega el registro en el log de eventos
      $evento = "Alta de nivel ". $fila_consulta['nombre_nivel'];    
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
        exit();
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

//////////////////////////
// ELIMINAR UN PUNTAJE //
/////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    if(!isset($_GET['nombre_nivel']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el nombre_nivel por POST.";
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
        //busca la descripción en la tabla
        $consulta = $dbConn->prepare("SELECT * FROM niveles where nombre_nivel=:nombre_nivel");
        $consulta->bindValue(':nombre_nivel', $_GET['nombre_nivel']);
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC); 
        if (!empty($fila_consulta)) 
        {
          if(empty($_GET['usuario']))
          {
            $respuesta['resultado']="ERROR";
            $respuesta['mensaje']="Debe informar el usuario.";
            echo json_encode(  $respuesta  );
            exit();              
          }
          $evento = "Eliminó el nivel ".$fila_consulta['nombre_nivel'];
          $statement = $dbConn->prepare("DELETE FROM niveles where nombre_nivel=:nombre_nivel");
          $statement->bindValue(':nombre_nivel', $_GET['nombre_nivel']);
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
          $respuesta['mensaje']="El nivel no existe en la tabla.";    
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

if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
  if(!isset($_GET['nombre_nivel']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el nombre_nivel por POST.";
    echo json_encode(  $respuesta  );
    exit(); 
  }elseif(!isset($_GET['puntaje_maximo']))
  {
    $respuesta['resultado']="ERROR";
    $respuesta['mensaje']="Debe enviar el puntaje_maximo por POST.";
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
    $input = $_GET;
    //Se busca si existe el nivel
    $consulta = $dbConn->prepare("SELECT * FROM niveles where nombre_nivel=:nombre_nivel");
    $consulta->bindParam(':nombre_nivel', $input['nombre_nivel']); 
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $consulta->execute();
    $fila_consulta= $consulta->fetch();
    try{
    	if($fila_consulta)
    	{
        if(empty($_GET['usuario']))
        {
          $respuesta['resultado']="ERROR";
          $respuesta['mensaje']="Debe informar el usuario.";
          echo json_encode(  $respuesta  );
          exit();              
        }        
  			$data=[				
  				'nombre_nivel' => $input['nombre_nivel'],
  				'puntaje_maximo' => $input['puntaje_maximo'],
  			];    		
  			$sql = "UPDATE niveles 
  			SET nombre_nivel = :nombre_nivel, puntaje_maximo = :puntaje_maximo
  			WHERE nombre_nivel = :nombre_nivel";
  			$statement = $dbConn->prepare($sql);     
  			$statement->execute($data);          		
    	}else
    	{
        $respuesta['resultado']="ERROR";
        $respuesta['mensaje']="El nivel Arkidian no existe en la tabla.";    
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
    $evento = "Se modificó el nivel de: " . $input['nombre_nivel'];    
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
    $statement->bindParam(':usuario', $input['usuario']);          
    $statement->execute();

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