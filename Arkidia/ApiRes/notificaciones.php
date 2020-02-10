<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
///////////////////////////////
//  CONSULTA DE COMENTARIOS  // 
//////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	$usuario = $_GET['usuario'];
	if(isset($usuario) && !empty($usuario))
	{
		///////////////////////////////
		// Notificaciones Pendientes //
		//////////////////////////////
		$indicador_visto = "N";
        $sql = $dbConn->prepare("SELECT * FROM notificaciones 
                                WHERE usuario = :usuario AND indicador_visto=:indicador_visto 
                                 ORDER BY fecha DESC");
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':indicador_visto', $indicador_visto);
        $sql->execute();   
        $fila_pendientes = $sql->fetchAll();

        $notificacionesPendientes = array();
        if(!empty($fila_pendientes))
        {
	        foreach ($fila_pendientes as $row) {
	            $sql = $dbConn->prepare("SELECT * FROM usuario_hijo 
	                                    where usuario=:usuario");
	            $sql->bindValue(':usuario', $row['usuario_origen']);
	            $sql->execute();
	            $usuario_remitente = $sql->fetch(PDO::FETCH_ASSOC);

	            $item = array(          
	              'avatarRemitente'   => $usuario_remitente['avatar'],
	              'usuarioRemitente'  => $usuario_remitente['usuario'],
	              'aliasRemitente'    => $usuario_remitente['alias'],
	              'tipo_notificacion' => $row['tipo_notificacion'],
	              'mensaje'           => $row['texto'],
	              'fechahora'         => $row['fecha'],
	              'secuencia'         => $row['secuencia'],
	              'estado'            => "1",
	              'idChallenge'       => $row['id_challenge'],
	              'usuario'           => $row['usuario']
	            );
	            array_push($notificacionesPendientes, $item);
	        }
	    }

		///////////////////////////
		// Notificaciones Vistas //
		//////////////////////////
		$indicador_visto = "S";
        $sql = $dbConn->prepare("SELECT * FROM notificaciones 
                                WHERE usuario = :usuario AND indicador_visto = :indicador_visto 
                                 ORDER BY fecha DESC");
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':indicador_visto', $indicador_visto);
        $sql->execute();   
        $fila_vistas = $sql->fetchAll();

        $notificacionesVistas = array();
        if(!empty($fila_vistas))
        {        
	        foreach ($fila_vistas as $row) {
	            $sql = $dbConn->prepare("SELECT * FROM usuario_hijo 
	                                    where usuario=:usuario");
	            $sql->bindValue(':usuario', $row['usuario_origen']);
	            $sql->execute();
	            $usuario_remitente = $sql->fetch(PDO::FETCH_ASSOC);

	            $item = array(          
	              'avatarRemitente'   => $usuario_remitente['avatar'],
	              'usuarioRemitente'  => $usuario_remitente['usuario'],
	              'aliasRemitente'    => $usuario_remitente['alias'],
	              'tipo_notificacion' => $row['tipo_notificacion'],
	              'mensaje'           => $row['texto'],	              
	              'fechahora'         => $row['fecha'],
	              'secuencia'         => $row['secuencia'],
	              'estado'            => "2",
	              'idChallenge'       => $row['id_challenge'],
	              'usuario'           => $row['usuario']
	            );
	            array_push($notificacionesVistas, $item);
	        }
	    }
		header("HTTP/1.1 200 OK");
        $respuesta['notificacionesPendientes'] = $notificacionesPendientes;
        $respuesta['notificacionesVistas']     = $notificacionesVistas;		
		echo json_encode(  $respuesta  );
		//echo json_encode($respuesta);
		exit();        

	}
}

////////////////////////////
// MODIFICACION A VISTOS //
///////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
	$usuario = $_GET['usuario'];
	if(!isset($usuario) AND empty($usuario))
	{
	  $respuesta['resultado']="ERROR";
	  $respuesta['mensaje']="Debe enviar por POST el usuario.";
	  echo json_encode(  $respuesta  );
	  exit();  	
	}else
	{
	    try{
	 		$indicador = "N";
	 		$indicador_visto ="S";
			$data=[
				'usuario' => $usuario,
				'indicador'       => $indicador,
				'indicador_visto' => $indicador_visto,
			];
			$sql = "UPDATE notificaciones 
			SET indicador_visto = :indicador_visto
			WHERE usuario = :usuario and indicador_visto = :indicador";
			$statement = $dbConn->prepare($sql);     
			$statement->execute($data);
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
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>