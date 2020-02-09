<?php
	include "config.php";
	include "utils.php";
	$dbConn =  connect($db);
//////////////////////////////////////////
//  CONSULTA DE TABLA CHALLENGE ALUMNO  //
/////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	if(isset($_GET['usuario']) and !empty($_GET['usuario']))
	{
		$usuario = $_GET['usuario'];
        //Busca usuario que subió el challenge
        $sql = $dbConn->prepare("SELECT * FROM usuario_hijo where usuario = :usuario");	        
        $sql->bindValue(':usuario', $usuario);
        $sql->execute();	        
        $usuario_hijo = $sql->fetch(PDO::FETCH_ASSOC);		

        //Busca el puntaje del usuario
        $sql = $dbConn->prepare("SELECT * FROM puntaje_alumno where usuario = :usuario");	        
        $sql->bindValue(':usuario', $usuario);
        $sql->execute();	        
        $puntos = $sql->fetch(PDO::FETCH_ASSOC);

        //busca el nivel que le corresponde al usuario
        $sql = $dbConn->prepare("SELECT * FROM niveles ORDER BY puntaje_maximo");	        
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
		$nivel = $sql->fetchAll();   	        
        $valor_menor=0;
        foreach ($nivel as $row) {        	
        	if($puntos['puntaje'] >= $valor_menor and $puntos['puntaje'] <= $row['puntaje_maximo'])
        	{
        		$nivel        = $row['nombre_nivel'];
        		$puntos_nivel = $row['puntaje_maximo'];
        		break;		
        	}
        	$valor_menor=$row['puntaje_maximo'];
        }

        //Cursos hechos por el usuario
		$sql = $dbConn->prepare("SELECT c.id_curso, c.nombre_curso, cont.url_imagen
		    FROM curso_alumno ca
		    INNER JOIN cursos c ON ca.id_curso = c.id_curso
		    INNER JOIN contenido_curso cont ON ca.id_curso = cont.id_curso
			WHERE ca.usuario = :usuario and ca.ind_completo = 1");
		$sql->bindValue(':usuario', $usuario);        
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC);
		$cursos = $sql->fetchAll();
		$cursos_hechos=array();
		$control="";
        foreach($cursos as $row)
        {
        	if($control != $row['id_curso'])
        	{
        		$item=array(
        		'id_curso'     => $row['id_curso'],
        		'nombre_curso' => $row['nombre_curso'],
        		'url_imagen'   => $row['url_imagen']
        		);
        		array_push($cursos_hechos, $item);
        	}
        	$control=$row['id_curso'];
        }   

		//Busca los desafíos subidos por el usuario
		$sql = $dbConn->prepare("SELECT id_curso, id_challenge, url_contenido from challenge_alumno where usuario = :usuario");
		$sql->bindValue(':usuario', $usuario);        
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC);
		$desafios_subidos = $sql->fetchAll();

		//Badges 

        $respuesta['usuario']          = $usuario_hijo['usuario'];
        $respuesta['alias']            = $usuario_hijo['alias'];
        $respuesta['avatar']           = $usuario_hijo['avatar'];
        $respuesta['puntos']           = $puntos['puntaje'];
        $respuesta['nivel']            = $nivel;
        $respuesta['puntos_nivel']     = $puntos_nivel;
        $respuesta['cursos_hechos']    = $cursos_hechos;
        $respuesta['desafios_subidos'] = $desafios_subidos;
        $respuesta['badges']           = "badges";
        header("HTTP/1.1 200 OK");
        echo json_encode(  $respuesta  );
        exit();
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>