<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
////////////////////
// ALTA DE CURSO //
///////////////////
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
	}elseif(!isset($input['id_curso']))
	{
		$respuesta['resultado']="ERROR";
		$respuesta['mensaje']="Debe enviar id_curso por POST.";    
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
		if(empty($input['id_curso']))
		{
		  $respuesta['resultado']="ERROR";
		  $respuesta['mensaje']="Debe completar el id_curso.";    
		  echo json_encode($respuesta);
		  exit();
		}elseif(empty($input['usuario']))
		{
		  $respuesta['resultado']="ERROR";
		  $respuesta['mensaje']="Debe indicar el usuario.";    
		  echo json_encode($respuesta);
		  exit();
		}else
		{
			//Alta en curso_alumno
		    try{
				$consulta = $dbConn->prepare("SELECT * FROM cursos where id_curso = :id_curso");
				$consulta->bindValue(':id_curso', $input['id_curso']);
				$consulta->execute();
				$fila_curso = $consulta->fetch(PDO::FETCH_ASSOC);
				if($fila_curso)
				{
					//Verifica si ya estaba inscripto
					$consulta = $dbConn->prepare("SELECT * FROM curso_alumno 
						where id_curso = :id_curso and usuario = :usuario");
					$consulta->bindValue(':id_curso', $input['id_curso']);
					$consulta->bindValue(':usuario', $input['usuario']);					
					$consulta->execute();
					$fila_curso_alumno = $consulta->fetchAll();
					if($fila_curso_alumno)
					{
						$respuesta['resultado']="OK";
						$respuesta['mensaje']="";	
						echo json_encode($respuesta);
						exit();			
					}
					$sql = "INSERT INTO curso_alumno
					    (id_curso, usuario, ind_completo)
					    VALUES
					    (:id_curso, :usuario, :ind_completo)";
					$ind_completo = "0";
					$statement = $dbConn->prepare($sql);          
					$statement->bindParam(':id_curso', $input['id_curso']);
					$statement->bindParam(':usuario', $input['usuario']);
					$statement->bindParam(':ind_completo', $ind_completo);         
					$statement->execute();
				}else
				{
					$respuesta['resultado']="ERROR";
					$respuesta['mensaje']="El curso indicado no existe.";    
					echo json_encode($respuesta);
					exit();							
				}
		    }catch(Exception $e)
		    {
				$e->getMessage();          
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']= "curso ".$e;
				echo json_encode(  $respuesta  );
				exit();
		    }
		    //Alta de contenido_alumno
		    try{
				$consulta2 = $dbConn->prepare("SELECT * FROM contenido_curso where id_curso = :id_curso");
				$consulta2->bindValue(':id_curso', $input['id_curso']);
				$consulta2->execute();
				$fila_contenido = $consulta2->fetchAll();
				if(!empty($fila_contenido))
				{
					foreach($fila_contenido as $row)
					{				
						$sql = "INSERT INTO contenido_alumno
						    (id_curso, id_contenido, usuario, porcentaje_avance)
						    VALUES
						    (:id_curso, :id_contenido, :usuario, :porcentaje_avance)";
						$porcentaje_avance = "0";
						$statement = $dbConn->prepare($sql);
						$statement->bindParam('id_curso', $row['id_curso']);
						$statement->bindParam(':id_contenido', $row['id_contenido']);
						$statement->bindParam(':usuario', $input['usuario']);
						$statement->bindParam(':porcentaje_avance', $porcentaje_avance);         
						$statement->execute();
					}
				}else
				{
					$respuesta['resultado']="ERROR";
					$respuesta['mensaje']="El curso no tiene contenido relacionado.";    
					echo json_encode($respuesta);
					exit();						
				}
		    }catch(Exception $e)
		    {
				$e->getMessage();          
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']= "contenido ".$e;
				echo json_encode(  $respuesta  );
				exit();
		    }    				
		    try{
		    	//Alta challenge
				$consulta = $dbConn->prepare("SELECT * FROM challenges_cursos where id_curso = :id_curso");
				$consulta->bindValue(':id_curso', $input['id_curso']);
				$consulta->execute();
				$fila_challenge = $consulta->fetchAll();
				if($fila_challenge)
				{
					foreach($fila_challenge as $row)
					{				
						$sql = "INSERT INTO challenge_alumno
						    (id_curso, id_challenge, usuario, ind_completo, tipo_archivo, url_contenido, ind_aprobado, aprobador, total_likes, total_comentarios)
						    VALUES
						    (:id_curso, :id_challenge, :usuario, :ind_completo, :tipo_archivo, :url_contenido, :ind_aprobado, :aprobador, :total_likes,
						    	:total_comentarios)";
						$cero = "0";
						$url_contenido="";
						$ind_aprobado="";
						$aprobador="";
						$statement = $dbConn->prepare($sql);
						$statement->bindParam(':id_curso', $input['id_curso']);        
						$statement->bindParam(':id_challenge', $row['id_challenge']);
						$statement->bindParam(':usuario', $input['usuario']);
						$statement->bindParam(':ind_completo', $ind_completo);   
						$statement->bindParam('tipo_archivo', $cero);
						$statement->bindParam('url_contenido', $url_contenido);
						$statement->bindParam('ind_aprobado', $ind_aprobado);
						$statement->bindParam('aprobador', $ind_aprobado);
						$statement->bindParam('total_likes', $cero);
						$statement->bindParam('total_comentarios', $cero);
						$statement->execute();
					}
				}else
				{
					$respuesta['resultado']="ERROR";
					$respuesta['mensaje']="El curso no tiene challenge relacionado.";    
					echo json_encode($respuesta);
					exit();						
				}				
			}catch(Exception $e)
			{
				$e->getMessage();          
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']= "challenge ".$e;
				echo json_encode(  $respuesta  );
				exit();
			}
			//Alta de puntaje inicial en cero
			try{
				$sql = "INSERT INTO puntaje_alumno
				    (usuario, puntaje)
				    VALUES
				    (:usuario, :puntaje)";
				$cero = "0";
				$statement = $dbConn->prepare($sql);
				$statement->bindParam(':usuario', $input['usuario']);
				$statement->bindParam('puntaje', $cero);
				$statement->execute();
			}catch(Exception $e)
			{
				$e->getMessage();          
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']= "puntaje_alumno".$e;
				echo json_encode(  $respuesta  );
				exit();
			}
			$evento = "Se inscribió en el curso ".$fila_curso['nombre_curso'];
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
				header("HTTP/1.1 200 OK");
				$respuesta['resultado']="OK";
				$respuesta['mensaje']=""; 			
				echo json_encode($respuesta);
				exit();
			}catch(Exception $e)
			{
				$e->getMessage();          
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']="log ".$e;
				echo json_encode(  $respuesta  );
				exit();
			}     
		}    
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	$input = $_GET;
	if(empty($input))//Si no envia ningún campo por POST
	{
		$respuesta['resultado']="ERROR";
		$respuesta['mensaje']="Debe enviar los campos para el alta.";    
		echo json_encode($respuesta);
		exit();    
	//Chequeo que envíe todos los campos que necesita la API para hacer el insert
	}elseif(!isset($input['id_curso']))
	{
		$respuesta['resultado']="ERROR";
		$respuesta['mensaje']="Debe enviar id_curso por POST.";    
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
		if(empty($input['id_curso']))
		{
		  $respuesta['resultado']="ERROR";
		  $respuesta['mensaje']="Debe completar el id_curso.";    
		  echo json_encode($respuesta);
		  exit();
		}elseif(empty($input['usuario']))
		{
		  $respuesta['resultado']="ERROR";
		  $respuesta['mensaje']="Debe indicar el usuario.";    
		  echo json_encode($respuesta);
		  exit();
		}else
		{
			try{
				//Prepara el array contenido para informar contenido_alumno
				$sql = $dbConn->prepare("SELECT cont.id_contenido, cont.orden, cont.nombre_contenido, 
										cont.url_contenido, cont.url_imagen,
										 contAl.id_curso, contAl.id_contenido, contAl.usuario, 
										 contAl.porcentaje_avance 
										FROM contenido_alumno contAl
										INNER JOIN contenido_curso  cont
										ON contAl.id_contenido = cont.id_contenido
										WHERE contAl.id_curso = :id_curso and contAl.usuario = :usuario");
				$sql->bindValue(':id_curso', $input['id_curso']);
				$sql->bindValue('usuario', $input['usuario']);
				$sql->execute();
				$fila_contenido = $sql->fetchAll();
				$contenido=array();
				foreach($fila_contenido as $row)
				{		
	                $item = array(
	                'id_orden'          => $row['orden'],
	                'nombre_contenido'  => $row['nombre_contenido'],
	                'url_contenido'     => $row['url_contenido'],
	                'url_imagen'		=> $row['url_imagen'],
	                'id_contenido'      => $row['id_contenido'],
	                'usuario'           => ($row['usuario']),
	                'porcentaje_avance' => $row['porcentaje_avance']	                       
	              	);
	              	array_push($contenido, $item);
				}
				//Prepara el array Challenge para informar Challenge_Alumno
				$sql = $dbConn->prepare("SELECT cha.id_challenge, cha.orden_challenge, cha.nombre_challenge,
										cha.detalle_challenge, chaAl.id_challenge, chaAl.usuario, 
										chaAl.ind_completo, chaAl.tipo_archivo, chaAl.url_contenido, 
										chaAl.ind_aprobado, chaAl.aprobador, chaAl.total_likes, 
										chaAl.total_comentarios
										FROM challenge_alumno chaAl
										INNER JOIN challenges_cursos cha
										ON chaAl.id_challenge = cha.id_challenge
										WHERE chaAl.id_curso=:id_curso and chaAl.usuario = :usuario");
				$sql->bindValue(':id_curso', $input['id_curso']);
				$sql->bindValue('usuario', $input['usuario']);				 
				$sql->execute();
				$fila_challenge = $sql->fetchAll();
				$challenge=array();
				foreach($fila_challenge as $row)
				{		
	                $item = array(
	                'id_orden_challenge' => $row['orden_challenge'],
	                'nombre_challenge'   => $row['nombre_challenge'],
	                'detalle_challenge'  => $row['detalle_challenge'],
	                'id_challenge'       => $row['id_challenge'],
	                'ind_completo'       => ($row['ind_completo']),
	                'tipo_archivo'       => $row['tipo_archivo'],
	                'url_contenido'      => $row['url_contenido'],
	                'ind_aprobado'       => $row['ind_aprobado'],
	                'aprobador'          => $row['aprobador'],
	                'total_likes'        => $row['total_likes'],
	                'total_comentarios'  => $row['total_comentarios']
	              	);
	              	array_push($challenge, $item);
				}
				$sql = $dbConn->prepare("SELECT * FROM cursos where id_curso=:id_curso");
				$sql->bindValue(':id_curso', $input['id_curso']);								 
				$sql->execute();
				$fila_curso = $sql->fetch(PDO::FETCH_ASSOC);						
				$evento = "Vista de curso: " . $fila_curso['nombre_curso'];
				$sql = $dbConn->prepare("SELECT ca.id_curso, ca.usuario, ca.ind_completo,
										c.id_categoria, c.nombre_curso, c.detalle_curso
										FROM curso_alumno ca
										INNER JOIN cursos c ON ca.id_curso = c.id_curso
										WHERE ca.id_curso = :id_curso and ca.usuario = :usuario");          
				$sql->bindValue(':id_curso', $input['id_curso']);
				$sql->bindValue('usuario', $input['usuario']);				
				$sql->execute();
				$fila_curso_alumno = $sql->fetch(PDO::FETCH_ASSOC);
				header("HTTP/1.1 200 OK");
				if($fila_curso_alumno){
					$respuesta['id_curso']        = $fila_curso_alumno['id_curso'];
					$respuesta['usuario']         = $fila_curso_alumno['usuario'];
					$respuesta['ind_completo']    = $fila_curso_alumno['ind_completo'];
					$respuesta['id_categoria']    = $fila_curso_alumno['id_categoria'];
					$respuesta['nombre_curso']    = $fila_curso_alumno['nombre_curso'];
					$respuesta['detalle_curso']   = $fila_curso_alumno['detalle_curso'];
					$respuesta['contenido']       = $contenido;
					$respuesta['challenge']       = $challenge;
					echo json_encode(  $respuesta  );
					exit();				
				}else{
					$respuesta['resultado']="ERROR";
					$respuesta['mensaje']="El usuario NO existe en la tabla.";
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
		}
	}
}

///////////////////////////
// ELIMINAR INSCRIPCION //
//////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    if(!isset($_GET['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario por POST.";
      echo json_encode(  $respuesta  );
      exit();         
    }elseif(!isset($_GET['id_curso']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el id_curso por POST.";
      echo json_encode(  $respuesta  );
      exit();        
    }elseif(empty($_GET['usuario']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe enviar el usuario para eliminar.";
      echo json_encode(  $respuesta  );
      exit();                 
    }elseif(empty($_GET['id_curso']))
    {
      $respuesta['resultado']="ERROR";
      $respuesta['mensaje']="Debe informar el id_curso que realiza la acción.";
      echo json_encode(  $respuesta  );
      exit();            
    }else
    {        
      try{
        $input = $_GET;
        //
        $consulta = $dbConn->prepare("SELECT * FROM curso_alumno
        										WHERE id_curso = :id_curso AND usuario = :usuario");
        $consulta->bindValue(':id_curso', $input['id_curso']);
        $consulta->bindValue(':usuario', $input['usuario']);
        $consulta->execute();
        $fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!empty($fila_consulta)) 
        {
        	//Elimina el curso del alumno
			$statement = $dbConn->prepare("DELETE FROM curso_alumno 
													WHERE id_curso = :id_curso AND usuario = :usuario");
			$statement->bindValue(':id_curso', $input['id_curso']);  
        	$statement->bindValue(':usuario', $input['usuario']);			        
			$statement->execute();

			//Consulta si tiene contenido del alumno
			$consulta = $dbConn->prepare("SELECT * FROM contenido_alumno
													WHERE id_curso = :id_curso AND usuario = :usuario");
			$consulta->bindValue(':id_curso', $input['id_curso']);
			$consulta->bindValue(':usuario', $input['usuario']);
			$consulta->execute();
			$fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
			if (!empty($fila_consulta))
			{
				//Elimina el contenido del alumno
				$statement = $dbConn->prepare("DELETE FROM contenido_alumno 
														WHERE id_curso = :id_curso AND usuario = :usuario");
				$statement->bindValue(':id_curso', $input['id_curso']);  
	        	$statement->bindValue(':usuario', $input['usuario']);	          
				$statement->execute();				
			}
			//Consulta si tiene challenge de alumno
			$consulta = $dbConn->prepare("SELECT * FROM challenge_alumno
													WHERE id_curso = :id_curso AND usuario = :usuario");
			$consulta->bindValue(':id_curso', $input['id_curso']);
			$consulta->bindValue(':usuario', $input['usuario']);
			$consulta->execute();
			$fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
			if (!empty($fila_consulta))
			{
				//Elimina el challenge del alumno
				$statement = $dbConn->prepare("DELETE FROM challenge_alumno 
														WHERE id_curso = :id_curso AND usuario = :usuario");
				$statement->bindValue(':id_curso', $input['id_curso']);  
	        	$statement->bindValue(':usuario', $input['usuario']);          
				$statement->execute();				
			}
			//Consulta si tiene puntaje de alumno
			$consulta = $dbConn->prepare("SELECT * FROM puntaje_alumno
													WHERE usuario = :usuario");
			$consulta->bindValue(':usuario', $input['usuario']);
			$consulta->execute();
			$fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
			if (!empty($fila_consulta))
			{
				//Elimina el puntaje del alumno
				$statement = $dbConn->prepare("DELETE FROM puntaje_alumno WHERE usuario = :usuario");	  
	        	$statement->bindValue(':usuario', $input['usuario']);          
				$statement->execute();				
			}

			//Graba registro en tabla Log
			$consulta = $dbConn->prepare("SELECT * FROM cursos
										WHERE id_curso = :id_curso");
			$consulta->bindValue(':id_curso', $input['id_curso']);
			$consulta->execute();
			$fila_consulta = $consulta->fetch(PDO::FETCH_ASSOC);
			$evento = "Eliminó la inscripción del curso ".$fila_consulta['nombre_curso'];
			//Agrega el registro en el log de eventos    
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$fecha_formateada = date("Y-m-d H:i:s",time());		
			$sql = "INSERT INTO log 
			      (fecha, evento, usuario)
			      VALUES
			      (:fecha, :evento, :usuario)";
			$statement = $dbConn->prepare($sql);  
			$statement->bindParam(':fecha', $fecha_formateada);
			$statement->bindParam(':evento', $evento);
			$statement->bindParam(':usuario', $_GET['usuario']);          
			$statement->execute();
			//Envía respuesta
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

header("HTTP/1.1 400 Bad Request");
?>