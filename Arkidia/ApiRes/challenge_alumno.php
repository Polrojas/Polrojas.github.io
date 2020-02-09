<?php
	include "config.php";
	include "utils.php";
	$dbConn =  connect($db);
//////////////////////////////////////////
//  CONSULTA DE TABLA CHALLENGE ALUMNO  //
/////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	if(isset($_GET['usuario']) and isset($_GET['id_curso']))
	{   
	  $evento="";
	  // Traerá todos los challenges vinculados al curso indicado
	    if(!empty($_GET['id_curso']) and !empty($_GET['usuario'])) // Si el campo no está vacío
	    {
	      try{
	        $sql = $dbConn->prepare("SELECT * FROM challenge_alumno where id_curso = :id_curso");
	        $sql->bindValue(':id_curso', $_GET['id_curso']);
	        $sql->execute();
	        $sql->setFetchMode(PDO::FETCH_ASSOC);
	        $challenges = $sql->fetchAll();
	        $evento = "Consulta los challenges subidos por los alumnos del curso" ;
			$respuesta=array();

	        foreach($challenges as $row)
	        {
	          //Busca que el usuario haya puesto like
	          $like = $dbConn->prepare("SELECT * FROM like_challenge 
	                                      where id_challenge=:id_challenge and usuario_challenge=:usuario_challenge
	                                      and usuario_like = :usuario_like");
	          $like->bindValue('id_challenge', $row['id_challenge']);	          
	          $like->bindValue('usuario_challenge', $row['usuario']);
	          $like->bindValue('usuario_like', $_GET['usuario']);
	          $like->execute();
	          $fila_like = $like->fetch(PDO::FETCH_ASSOC);

	        //Busca usuario que subió el challenge
	        $sql = $dbConn->prepare("SELECT * FROM usuario_hijo 
	        							where usuario = :usuario");	        
	        $sql->bindValue(':usuario', $row['usuario']);
	        $sql->execute();	        
	        $usuario_hijo = $sql->fetch(PDO::FETCH_ASSOC);

	          

	          if(!empty($fila_like) and $fila_like['usuario_like'] == $_GET['usuario'])	          
	          {
	            $ind_like = "1";
	          }
	          else
	          {
	            $ind_like ="0";
	          }

				$item = array(
					'id_curso'          => $row['id_curso'],
					'id_challenge'      => $row['id_challenge'],
					'usuario'           => $row['usuario'],
					'alias'             => $usuario_hijo['alias'],
					'ind_completo'      => $row['ind_completo'],
					'tipo_archivo'      => $row['tipo_archivo'],
					'url_contenido'     => $row['url_contenido'],
					'ind_aprobado'      => $row['ind_aprobado'],
					'aprobador'         => $row['aprobador'],
					'total_likes'       => $row['total_likes'],
					'total_comentarios' => $row['total_comentarios'],
					'ind_like'          => $ind_like             
				);
				array_push($respuesta, $item);
				$ind_like ="0";
				$fila_like = "";
	        }

	        header("HTTP/1.1 200 OK");
	        echo json_encode($respuesta);

	      }catch (Exception $e){
	        $e->getMessage();          
	        $respuesta['resultado']="ERROR";
	        $respuesta['mensaje']=$e;
	        echo json_encode(  $respuesta  );      
	      }
	    }else{
	      $respuesta['resultado']="ERROR";
	      $respuesta['mensaje']="Debe completar el id_curso y usuario";
	      echo json_encode(  $respuesta  );               
	    }
	//Consulta puntual
	}elseif(isset($_GET['usuario']) and isset($_GET['id_challenge']) and isset($_GET['usuario_challenge']))
	{	    
	    if(!empty($_GET['usuario']) and !empty($_GET['id_challenge']) and !empty($_GET['usuario_challenge'])) // Si el campo no está vacío
	    {
	      try{
	      	//Busqueda puntual
	        $sql = $dbConn->prepare("SELECT * FROM challenge_alumno 
	        							where id_challenge = :id_challenge and usuario = :usuario");
	        $sql->bindValue(':id_challenge', $_GET['id_challenge']);
	        $sql->bindValue(':usuario', $_GET['usuario_challenge']);
	        $sql->execute();	        
	        $challenges = $sql->fetch(PDO::FETCH_ASSOC);
	        
	        //Busca usuario que subió el challenge
	        $sql = $dbConn->prepare("SELECT * FROM usuario_hijo 
	        							where usuario = :usuario");	        
	        $sql->bindValue(':usuario', $_GET['usuario_challenge']);
	        $sql->execute();	        
	        $usuario_hijo = $sql->fetch(PDO::FETCH_ASSOC);
	        
	        //Busca el curso
	        $sql = $dbConn->prepare("SELECT * FROM cursos 
	        							where id_curso = :id_curso");
	        $sql->bindValue(':id_curso', $challenges['id_curso']);	        
	        $sql->execute();	        
	        $curso = $sql->fetch(PDO::FETCH_ASSOC);
	        
	        //Busca el challenge
	        $sql = $dbConn->prepare("SELECT * FROM challenges_cursos 
	        							where id_challenge = :id_challenge");
	        $sql->bindValue(':id_challenge', $_GET['id_challenge']);	        
	        $sql->execute();	        
	        $challenge_curso = $sql->fetch(PDO::FETCH_ASSOC);
				        
			//Busca que el usuario haya puesto like
			$like = $dbConn->prepare("SELECT * FROM like_challenge 
									where id_challenge=:id_challenge and usuario_challenge=:usuario_challenge
									and usuario_like = :usuario_like");
			$like->bindValue('id_challenge', $challenges['id_challenge']);	          
			$like->bindValue('usuario_challenge', $challenges['usuario']);
			$like->bindValue('usuario_like', $_GET['usuario']);
			$like->execute();
			$fila_like = $like->fetch(PDO::FETCH_ASSOC);      
			if(!empty($fila_like) and $fila_like['usuario_like'] == $_GET['usuario'])	          
			{
				$ind_like = "1";
			}
			else
			{
				$ind_like ="0";
			}   	        
	        $evento = "Consulta los challenges subidos por los alumnos del curso" ;

			$respuesta['usuario']           = $challenges['usuario'];
			$respuesta['alias']             = $usuario_hijo['alias'];
			$respuesta['avatar']            = $usuario_hijo['avatar'];
			$respuesta['id_curso']          = $challenges['id_curso'];
			$respuesta['nombre_curso']      = $curso['nombre_curso'];
			$respuesta['detalle_curso']     = $curso['detalle_curso'];
			$respuesta['detalle_challenge'] = $challenge_curso['detalle_challenge'];
			$respuesta['url_challenge']     = $challenges['url_contenido'];
			$respuesta['fechahora']         = "2020-01-25 14:24:54";
			$respuesta['total_likes']       = $challenges['total_likes'];
			$respuesta['total_comentarios'] = $challenge['total_comentarios'];
			$respuesta['ind_like']          = $ind_like;          

	        header("HTTP/1.1 200 OK");
	        echo json_encode($respuesta);

	      }catch (Exception $e){
	        $e->getMessage();          
	        $respuesta['resultado']="ERROR-PUTO!!";
	        $respuesta['mensaje']=$e;
	        echo json_encode(  $respuesta  );      
	      }
	    }else{
	      $respuesta['resultado']="ERROR";
	      $respuesta['mensaje']="Debe completar el id_categoría y usuario";
	      echo json_encode(  $respuesta  );               
	    }
	}elseif(!isset($_GET['usuario']) and !isset($_GET['id_curso']) and isset($_GET['id_challenge']))
	{   
	  $evento="";
	  // Traerá todos los que existen para ese challenge en particular
	    if(!empty($_GET['id_challenge'])) // Si el campo no está vacío
	    {
	      try{
	        $sql = $dbConn->prepare("SELECT * FROM challenge_alumno where id_challenge = :id_challenge");
	        $sql->bindValue(':id_challenge', $_GET['id_challenge']);
	        $sql->execute();
	        $sql->setFetchMode(PDO::FETCH_ASSOC);
	        $challenges = $sql->fetchAll();
	        $evento = "Consulta los challenges subidos por los alumnos del curso" ;
			$respuesta=array();

	        foreach($challenges as $row)
	        {
	          //Busca que el usuario haya puesto like

				$item = array(
					'id_curso'          => $row['id_curso'],
					'id_challenge'      => $row['id_challenge'],
					'usuario'           => $row['usuario'],
					'ind_completo'      => $row['ind_completo'],
					'tipo_archivo'      => $row['tipo_archivo'],
					'url_contenido'     => $row['url_contenido'],
					'ind_aprobado'      => $row['ind_aprobado'],
					'aprobador'         => $row['aprobador'],
					'total_likes'       => $row['total_likes'],
					'total_comentarios' => $row['total_comentarios'],
					'ind_like'          => "0"
				);
				array_push($respuesta, $item);
	        }

	        header("HTTP/1.1 200 OK");
	        echo json_encode($respuesta);

	      }catch (Exception $e){
	        $e->getMessage();          
	        $respuesta['resultado']="ERROR";
	        $respuesta['mensaje']=$e;
	        echo json_encode(  $respuesta  );      
	      }
	    }else{
	      $respuesta['resultado']="ERROR";
	      $respuesta['mensaje']="Debe completar el id_challenge";
	      echo json_encode(  $respuesta  );               
	    }

	}
	  //Se registrará cuando exsita un evento ejecutado por un usuario
	$usuario=$_GET['usuario'];
	if($evento != "" and $usuario != "") { 
		if(isset($_GET['usuario']) and !empty($_GET['usuario']))
		{
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
		    $statement->bindParam(':usuario', $usuario);          
		    $statement->execute();
		}
	}   
	exit();
}




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