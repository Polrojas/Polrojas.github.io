<?php
	include "config.php";
	include "utils.php";	
	$dbConn =  connect($db);
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$input= $_POST;
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	$fecha_formateada = date("Y-m-d H:i:s",time());
	try
	{
		$data=[     
			'usuario'       => $input['usuario_challenge'],
			'id_curso'      => $input['id_curso'],
			'id_challenge'  => $input['id_challenge'],
			'fecha'         => $fecha_formateada,
			'url_contenido' => $input['imagen'],
			'ind_aprobado'  => "N",
			'ind_completo'  => "1"
		];    
		$sql = "UPDATE challenge_alumno
		SET url_contenido = :url_contenido, fecha = :fecha,
			ind_aprobado = :ind_aprobado, ind_completo = :ind_completo
		WHERE usuario = :usuario and id_challenge = :id_challenge and id_curso = :id_curso";
		$statement = $dbConn->prepare($sql);     
		$statement->execute($data);
		//Busco el id_curso
		$sql = $dbConn->prepare("SELECT id_curso from challenge_alumno 
			where usuario = :usuario and id_challenge = :id_challenge");
		$sql->bindValue(':usuario', $input['usuario_challenge']);
		$sql->bindValue(':id_challenge', $input['id_challenge']);       
		$sql->execute();						
		$challenges = $sql->fetch(PDO::FETCH_ASSOC);						
		cursoCompleto($db, $challenges['id_curso'], $input['usuario_challenge']);

		//Se envía la respuesta
		header("HTTP/1.1 200 OK");
		$respuesta['resultado']="OK";
		$respuesta['mensaje']="";		
		echo json_encode($respuesta);		
		exit();
		
	}catch(Exception $e)
	{
		$e->getMessage();          
		$respuesta['resultado']="ERROR";
		$respuesta['mensaje']= "Modificación de challenge_alumno " . $e;
		echo json_encode(  $respuesta  );
		exit();
	}	
}

//////////////////////////////////////////
//  CONSULTA DE TABLA CHALLENGE ALUMNO  //
/////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	$aprobado = "S";
	if(isset($_GET['usuario']) and isset($_GET['id_curso']))
	{
		//input: id_curso - usuario
		$usuario=$_GET['usuario'];
		$evento="";
		// Traerá todos los challenges vinculados al curso indicado
	    if(!empty($_GET['id_curso']) and !empty($_GET['usuario'])) // Si el campo no está vacío
	    {
	      try{
	        $sql = $dbConn->prepare("SELECT * FROM challenge_alumno 
	        	where id_curso = :id_curso and ind_aprobado=:ind_aprobado");
	        $sql->bindValue(':id_curso', $_GET['id_curso']);
	        $sql->bindValue(':ind_aprobado', $aprobado);
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
	        exit();     
	      }
	    }else{
	      $respuesta['resultado']="ERROR";
	      $respuesta['mensaje']="Debe completar el id_curso y usuario";
	      echo json_encode(  $respuesta  );
	      exit();            
	    }
	//Consulta puntual
	}elseif(isset($_GET['usuario']) and isset($_GET['id_challenge']) and isset($_GET['usuario_challenge']))
	{	 
		//input: id_challenge - usuario_challenge - usuario  
		$usuario=$_GET['usuario']; 
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
			$respuesta['fechahora']         = $challenges['fecha'];
			$respuesta['total_likes']       = $challenges['total_likes'];
			$respuesta['total_comentarios'] = $challenges['total_comentarios'];
			$respuesta['ind_like']          = $ind_like;          

	        header("HTTP/1.1 200 OK");
	        echo json_encode($respuesta);
	        
	      }catch (Exception $e){
	        $e->getMessage();          
	        $respuesta['resultado']="ERROR";
	        $respuesta['mensaje']=$e;
	        echo json_encode(  $respuesta  );
	        exit();   
	      }
	    }else{
	      $respuesta['resultado']="ERROR";
	      $respuesta['mensaje']="Debe completar el id_categoría y usuario";
	      echo json_encode(  $respuesta  );
	      exit();             
	    }
	}elseif(!isset($_GET['usuario']) and !isset($_GET['id_curso']) and isset($_GET['id_challenge']))
	{   
		$usuario="";
		$evento="";
		// input: id_challenge
		// Traerá todos los que existen para ese challenge en particular
	    if(!empty($_GET['id_challenge'])) // Si el campo no está vacío
	    {
	      try{
	      	$aprobado="S";
	        $sql = $dbConn->prepare("SELECT * FROM challenge_alumno 
	        	where id_challenge = :id_challenge and ind_aprobado=:ind_aprobado");
	        $sql->bindValue(':id_challenge', $_GET['id_challenge']);
	        $sql->bindValue(':ind_aprobado', $aprobado);
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
	        exit();      
	      }
	    }else{
	      $respuesta['resultado']="ERROR";
	      $respuesta['mensaje']="Debe completar el id_challenge";
	      echo json_encode(  $respuesta  ); 
	      exit();              
	    }

	}elseif(isset($_GET['administrador']))
	{
		//input: administrador
		//Devuelve todos los challenge que no estén aprobados
		$usuario = $_GET['administrador'];
		try{
		$ind_aprobado = "N";
		$sql = $dbConn->prepare("SELECT * FROM challenge_alumno where ind_aprobado = :ind_aprobado");
		$sql->bindValue(':ind_aprobado', $ind_aprobado);
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC);
		$challenges = $sql->fetchAll();
		$evento = "Consulta los challenges pendientes de aprobar." ;
		$respuesta=array();

		foreach($challenges as $row)
		{
		  //Busca que el usuario haya puesto like

			$item = array(
				'id_curso'          => $row['id_curso'],
				'id_challenge'      => $row['id_challenge'],
				'usuario'           => $row['usuario'],	
				'url_contenido'     => $row['url_contenido'],	
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
			exit();
		}		
	  
	}elseif(isset($_GET['cantidad_challenge']) )
	{
		//input: cantidad_challenge
		//Devuelve los últimos challenge cargados, conforme a un parámetro
		$cantidad_challenge = $_GET['cantidad_challenge'];
		try{
			$ind_aprobado = "S";			
			$sql = $dbConn->prepare("SELECT * FROM challenge_alumno where ind_aprobado = :ind_aprobado
				order by fecha DESC LIMIT $cantidad_challenge");
			$sql->bindValue(':ind_aprobado', $ind_aprobado);			
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			$challenges = $sql->fetchAll();
			$evento = "Consulta los challenges pendientes de aprobar." ;
			$respuesta=array();

			foreach($challenges as $row)
			{
				$item = array(
					'id_curso'          => $row['id_curso'],
					'id_challenge'      => $row['id_challenge'],
					'usuario'           => $row['usuario'],	
					'url_contenido'     => $row['url_contenido'],	
				);
				array_push($respuesta, $item);
			}

			header("HTTP/1.1 200 OK");
			echo json_encode($respuesta);
			
		}catch (Exception $e){
			$e->getMessage();          
			$respuesta['resultado']="ERROR";
			$respuesta['mensaje']= "puto " . $e;
			echo json_encode(  $respuesta  );
			exit();
		}				
	}
	//Se registrará cuando exista un evento ejecutado por un usuario

	if($evento != "" and $usuario != "") { 
		if(isset($_GET['usuario']) and !empty($_GET['usuario']))
		{
			registroLog($db, $evento, $usuario);
		}
	}   
	exit();
}




	////////////////////////////
	// ACTUALIZACION DE DATOS //
	///////////////////////////
	if ($_SERVER['REQUEST_METHOD'] == 'PUT')
	{
		$input = $_GET;
		if(!isset($input['id_challenge']))
		{
			$respuesta['resultado']="ERROR";
			$respuesta['mensaje']="Debe enviar por POST el id_challenge.";
			echo json_encode(  $respuesta  );
			exit();
		}elseif(empty($input['id_challenge']))
		{
			$respuesta['resultado']="ERROR";
			$respuesta['mensaje']="Debe enviar el id_challenge.";
			echo json_encode(  $respuesta  );
			exit();
		}elseif(!isset($input['usuario_challenge']))
		{
		  $respuesta['resultado']="ERROR";
		  $respuesta['mensaje']="Debe enviar por POST el usuario_challenge.";
		  echo json_encode(  $respuesta  );
		  exit();
		}elseif(empty($input['usuario_challenge']))
		{
		  $respuesta['resultado']="ERROR";
		  $respuesta['mensaje']="Debe completar el usuario_challenge.";
		  echo json_encode(  $respuesta  );
		  exit();			
		}else
		{

			$challenge = $input['id_challenge'];
			///////////////////////////////////////////////////////////////////////////////
			//Se determina que tipo de usuario es el que está realizando la acción
			$consulta = $dbConn->prepare("SELECT usuario FROM usuario_hijo where usuario=:usuario");
			$consulta->bindValue(':usuario', $input['usuario_challenge']);
			$consulta->execute();
			$usuario_hijo = $consulta->fetch(PDO::FETCH_ASSOC);
			$consulta = $dbConn->prepare("SELECT mail FROM usuario_padre where mail=:mail");
			$consulta->bindValue(':mail', $input['usuario_challenge']);
			$consulta->execute();
			$usuario_padre = $consulta->fetch(PDO::FETCH_ASSOC);
			if(isset($input['usuario']) && !empty($input['usuario']))
			{
				$usuario_padre = $consulta->fetch(PDO::FETCH_ASSOC);
				$consulta = $dbConn->prepare("SELECT mail FROM usuario_administrador where mail=:mail");
				$consulta->bindValue(':mail', $input['usuario']);
				$consulta->execute();
				$usuario_administrador = $consulta->fetch(PDO::FETCH_ASSOC);				
			}

			//////////////////////////////////////////////////////////////////////////////
			//Se busca el challenge puntual
			$consulta = $dbConn->prepare("SELECT * FROM challenge_alumno 
				where id_challenge=:id_challenge and usuario=:usuario");
			$consulta->bindValue(':id_challenge', $challenge);
			$consulta->bindValue(':usuario', $input['usuario_challenge']);
			$consulta->execute();
			$fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);        
			/////////////////////////////////////////////////////////////////////////////////
			//Se completa la variable $evento
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
			////////////////////////////////////////////////////////////////////////////////// 
			//Si el usuario_challenge ingresado está en la tabla usuario_hijo le permite subir 
			//el challenge y actualiza challenge_alumno
			if(!empty($usuario_hijo['usuario']) && !isset($_GET['usuario']) && empty($_GET['usuario']))
			{
				$usuario=$input['usuario_challenge'];
				//Input: id_challenge - usuario_challenge - url_contenido
				if(!isset($input['url_contenido']))
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
					date_default_timezone_set('America/Argentina/Buenos_Aires');
					$fecha_formateada = date("Y-m-d H:i:s",time());
					try
					{
						$ind_completo = "1";
						$ind_aprobado = "N";
						$data=[
							'id_challenge' => $input['id_challenge'],
							'usuario'      => $input['usuario_challenge'],
							'ind_completo' => $ind_completo,
							'fecha'        => $fecha_formateada,
							'url_contenido'=> $input['url_contenido'],
							'ind_aprobado' => $ind_aprobado,      
						];
						$sql = "UPDATE challenge_alumno 
						SET ind_completo = :ind_completo, fecha = :fecha,
							url_contenido = :url_contenido,	ind_aprobado = :ind_aprobado
						WHERE id_challenge = :id_challenge and usuario = :usuario";
						$statement = $dbConn->prepare($sql);     
						$statement->execute($data);
						//Busco el id_curso
						$sql = $dbConn->prepare("SELECT id_curso from challenge_alumno 
							where usuario = :usuario and id_challenge = :id_challenge");
						$sql->bindValue(':usuario', $input['usuario_challenge']);
						$sql->bindValue(':id_challenge', $input['id_challenge']);       
						$sql->execute();						
						$challenges = $sql->fetch(PDO::FETCH_ASSOC);						
						cursoCompleto($db, $challenges['id_curso'], $input['usuario_challenge']);

					}catch(Exception $e)
					{
					  $e->getMessage();          
					  $respuesta['resultado']="ERROR";
					  $respuesta['mensaje']=$e;
					  echo json_encode(  $respuesta  );
					  exit();
					}
				}
			////////////////////////////////////////////////////////////////////////////////////////
			//Si usuario ingresado es administrador permite aprobar / rechazar
			}elseif(!empty($usuario_administrador['mail']))

			{
				$usuario=$input['usuario'];
				
				//Input: id_challenge - usuario_challenge - usuario - ind_aprobado
				if(!isset($input['ind_aprobado']))
				{
				  $respuesta['resultado']="ERROR";
				  $respuesta['mensaje']="Debe enviar por POST el ind_aprobado.";
				  echo json_encode(  $respuesta  );
				  exit();
				}elseif(empty($input['ind_aprobado']))
				{
					$respuesta['resultado']="ERROR";
					$respuesta['mensaje']="Debe completar el ind_aprobado.";
					echo json_encode(  $respuesta  );
					exit();
				}else
				{ 

					if(empty($usuario_hijo['usuario']))
					{
						$respuesta['resultado']="ERROR";
						$respuesta['mensaje']="El usuario_challenge no existe.";
						echo json_encode(  $respuesta  );
						exit();
					}
					try
					{

						$data=[
							'id_challenge' => $input['id_challenge'],					    
							'usuario'      => $input['usuario_challenge'],
							'ind_aprobado' => $input['ind_aprobado'],
							'aprobador'	   => $usuario,					    		            
						];
						$sql = "UPDATE challenge_alumno 
						SET ind_aprobado = :ind_aprobado, aprobador = :aprobador
						WHERE id_challenge = :id_challenge and usuario = :usuario";
						$statement = $dbConn->prepare($sql);     
						$statement->execute($data);

					  //Se busca el id_curso
						$sql = $dbConn->prepare("SELECT * FROM challenge_alumno
								 WHERE id_challenge = :id_challenge and usuario = :usuario");
						$sql->bindValue(':id_challenge', $input['id_challenge']);
						$sql->bindValue(':usuario', $input['usuario_challenge']);
						$sql->execute();
						$fila_challenge = $sql->fetch(PDO::FETCH_ASSOC);
						$ind_completo=1;
						$data=[
							'id_curso'     => $fila_challenge['id_curso'],					    
							'usuario'      => $input['usuario_challenge'],
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
					if($input['ind_aprobado']=="S")
					{
						//////////////////////////////////////////////////////////////////////////////////////////////
						//Buscar valor del puntaje que corresponde al challenge
						$puntaje = $dbConn->prepare("SELECT * FROM puntaje where evento='challenge'");
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
							$puntaje_alumno->bindParam(':usuario', $input['usuario_challenge']);
							$puntaje_alumno->execute();
							$fila_puntaje = $puntaje_alumno->fetch(PDO::FETCH_ASSOC);
							$puntos+=$fila_puntaje['puntaje'];          
							$data=[     
							'usuario' => $input['usuario_challenge'],
							'puntaje' => $puntos,
							];        
							$sql = "UPDATE puntaje_alumno
							SET puntaje = :puntaje
							WHERE usuario = :usuario";
							$statement = $dbConn->prepare($sql);     
							$statement->execute($data);
							//////////////////////////////////////////////////////////////////////////////////////
						}
					}
				}		
			}elseif(!empty($usuario_padre['mail']) && 
					(!isset($_GET['usuario']) && empty($_GET['usuario'])))
			{
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']="Actividad exclusiva para Arkidian.";
				echo json_encode(  $respuesta  );
				exit();			
			}else
			{
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']="No se realizó ninguna acción, verifique el usuario.";
				echo json_encode(  $respuesta  );
				exit();
			}
			//Agrega el registro en el log de eventos    
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$fecha_formateada = date("Y-m-d H:i:s",time());
			//Graba registro en tabla Log
			try
			{
				registroLog($db, $evento, $usuario);
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

		}
	}
?>