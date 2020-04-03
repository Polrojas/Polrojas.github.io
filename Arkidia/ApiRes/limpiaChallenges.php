<?php
	include "config.php";
	include "utils.php";	
	$dbConn =  connect($db);
	///////////////////////////////////////////////////////
	// LIMPIA LOS CHALLENGES DE UN USUARIO EN PARTICULAR //
	//////////////////////////////////////////////////////
	if ($_SERVER['REQUEST_METHOD'] == 'PUT')
	{
		$input = $_GET;

		if(!isset($input['usuario_challenge']))
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
			//////////////////////////////////////////////////////////////////////////////
			//Se busca el challenge puntual
			$consulta = $dbConn->prepare("SELECT * FROM challenge_alumno 
				where usuario=:usuario");
			$consulta->bindValue(':usuario', $input['usuario_challenge']);
			$consulta->execute();
			$fila_consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

			$usuario=$input['usuario_challenge'];
			if($fila_consulta)
			{
				$contar=0;
				//Input: usuario_challenge
				foreach($fila_consulta as $row) {
					try
					{						
						$cero  = "0";
						$vacio = "";
						$fecha = NULL;						
						$data  =[
							'id_challenge'      => $row['id_challenge'],
							'usuario'           => $usuario,
							'ind_completo'      => $cero,
							'fecha'             => $fecha,
							'url_contenido'     => $vacio,
							'ind_aprobado'      => $vacio, 
							'aprobador'         => $vacio,
							'total_likes'       => $cero,
							'total_comentarios' => $cero
						];
						$sql = "UPDATE challenge_alumno 
						SET ind_completo = :ind_completo, fecha = :fecha,
							url_contenido = :url_contenido,	ind_aprobado = :ind_aprobado,
							aprobador = :aprobador, total_likes = :total_likes, 
							total_comentarios = :total_comentarios
						WHERE id_challenge = :id_challenge AND usuario = :usuario";						
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
				}
				
				header("HTTP/1.1 200 OK");
				$respuesta['resultado']="OK";
				$respuesta['mensaje']="";		
				echo json_encode($respuesta);		
				exit();
			}else{
				$respuesta['resultado']="ERROR";
				$respuesta['mensaje']="El usuario no está en la tabla.";
				echo json_encode(  $respuesta  );
				exit();				
			}
		}
	}
?>