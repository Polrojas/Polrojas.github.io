<?php
	include "config.php";
	include "utils.php";
	$dbConn =  connect($db);
	////////////////////////////
	// ACTUALIZACION DE DATOS //
	///////////////////////////
	if ($_SERVER['REQUEST_METHOD'] == 'PUT')
	{
		if(!isset($_GET['id_challenge']))
		{
			$respuesta['resultado']="ERROR";
			$respuesta['mensaje']="Debe enviar por POST el id_challenge.";
			echo json_encode(  $respuesta  );
			exit();
		}elseif(empty($_GET['id_challenge']))
		{
			$respuesta['resultado']="ERROR";
			$respuesta['mensaje']="Debe enviar el id_challenge.";
			echo json_encode(  $respuesta  );
			exit();
		}else
		{
			$input = $_GET;
			$challenge = $input['id_challenge'];

			$consulta = $dbConn->prepare("SELECT * FROM challenge_alumno where id_challenge=:id_challenge");
			$consulta->bindValue(':id_challenge', $challenge);
			$consulta->execute();
			$fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);        
			
			//Consulta descripción de challenge
			$consulta = $dbConn->prepare("SELECT * FROM challenges_cursos where id_challenge=:id_challenge");
			$consulta->bindValue(':id_challenge', $challenge);
			$consulta->execute();
			$challenge_nombre = $consulta->fetch(PDO::FETCH_ASSOC);		
			//Consulta descripción del curso
		    $sql = $dbConn->prepare("SELECT * FROM cursos where id_curso = :id_curso");              
		    $sql->bindValue(':id_curso', $challenge_nombre['id_curso']);              
		    $sql->execute();            
		    $fila_curso = $sql->fetch(PDO::FETCH_ASSOC);			

			$evento = "Se modificó el challenge_nombre ".$challenge_nombre['nombre_challenge']."  del curso ". $fila_curso['nombre_curso'];
			 
			if(!isset($input['usuario']))
			{
			  $respuesta['resultado']="ERROR";
			  $respuesta['mensaje']="Debe enviar por POST el usuario.";
			  echo json_encode(  $respuesta  );
			  exit();
			}elseif(empty($input['usuario']))
			{
			  $respuesta['resultado']="ERROR";
			  $respuesta['mensaje']="Debe completar el usuario.";
			  echo json_encode(  $respuesta  );
			  exit();
			}elseif(!isset($input['ind_completo']))
			{
			  $respuesta['resultado']="ERROR";
			  $respuesta['mensaje']="Debe enviar por POST el ind_completo.";
			  echo json_encode(  $respuesta  );
			  exit();
			}elseif(empty($input['ind_completo']))
			{
			  $respuesta['resultado']="ERROR";
			  $respuesta['mensaje']="Debe completar el ind_completo.";
			  echo json_encode(  $respuesta  );
			  exit();   	
			}elseif(!isset($input['url_contenido']))
			{
			  $respuesta['resultado']="ERROR";
			  $respuesta['mensaje']="Debe enviar por POST el url_contenido.";
			  echo json_encode(  $respuesta  );
			  exit();
			}elseif(empty($input['url_contenido']))
			{
			  $respuesta['resultado']="ERROR";
			  $respuesta['mensaje']="Debe completar el url_contenido.";
			  echo json_encode(  $respuesta  );
			  exit();   
			}elseif($fila_consulta)
			{
				try{

				  $data=[
				    'id_challenge' => $input['id_challenge'],
				    'usuario' => $input['usuario'],
				    'ind_completo' => $input['ind_completo'],
				    'url_contenido' => $input['url_contenido'],        
				  ];
				  $sql = "UPDATE challenge_alumno 
				  SET ind_completo = :ind_completo, url_contenido = :url_contenido
				  WHERE id_challenge = :id_challenge and usuario = :usuario";
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
				
				//Graba registro en tabla Log
				try
				{
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
				}catch(Exception $e)
				{
					$e->getMessage();          
					$respuesta['resultado']="ERROR";
					$respuesta['mensaje']=$e;
					echo json_encode(  $respuesta  );
					exit();
				}
			}else
			{
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']="El challenge_alumno NO existe.";
				echo json_encode(  $respuesta  );
				exit();
			}
		}
	}
?>