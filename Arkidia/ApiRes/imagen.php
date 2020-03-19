<?php
require './aws/aws-autoloader.php';

/////////////////////////
// ALTA DE COMENTARIOS //
////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

		if( $_FILES['imagen']){

			$fileName     = $_FILES["imagen"]["name"];
			$fileTmpLoc   = $_FILES["imagen"]["tmp_name"];
			$fileType     = $_FILES["imagen"]["type"];
			$fileSize     = $_FILES["imagen"]["size"];
			$fileErrorMsg = $_FILES["imagen"]["error"];

			$permitidos = array("image/jpg",  "image/JPG", 
								"image/jpeg", "image/JPEG",
								"image/gif",  "image/GIF",
								"image/png",  "image/PNG");
			//$limite_kb  = 2000;
			//Se crea el objeto cliente con los datos para acceder
			if(in_array($_FILES["imagen"]["type"], $permitidos))//&& $fileSize <= $limite_kb * 1024)
			{
				$s3 = new Aws\S3\S3Client([
					'region'  => 'us-east-1',
					'version' => 'latest',
					'credentials' => [
						'key'    => "AKIAVBAXSKP7LE3ABCVV",
						'secret' => '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',
					]
				]);		
				$punto=strpos($fileName, ".");
				$extension=strtolower(substr($fileName, $punto));
			    date_default_timezone_set('America/Argentina/Buenos_Aires');
			    $fecha_formateada = date("Y-m-d H:i:s",time());				
				$resultado = $s3->putObject([
					'Bucket'       => 'arkidia',
					'Key'          => "images/" . $fecha_formateada . $extension, //$fileName,
					'SourceFile'   => $fileTmpLoc,
					'ACL'          => 'public-read',
                    'StorageClass' => 'STANDARD_IA'								
				]);
				//echo json_encode(var_dump($resultado));
			    $imagen = $resultado["ObjectURL"];
				if($imagen != null)
				{
			        $respuesta['url']=$imagen;
			        $respuesta['tipo_archivo']="IMAGEN";
			        echo json_encode(  $respuesta  );
			        exit();		
				}else
				{
			        $respuesta['resultado']="ERROR";
			        $respuesta['mensaje']="No se ha podido subir la image, intente nuevamente.";
			        echo json_encode(  $respuesta  );
			        exit();		
				}								
			}else
			{
		        $respuesta['resultado']="ERROR";
		        $respuesta['mensaje']="Solo se aceptan archivos con formato de imagen.";
		        echo json_encode(  $respuesta  );
		        exit();		
			}
		}else
		{	               
	        $respuesta['resultado']="ERROR";
	        $respuesta['mensaje']="Debe seleccionar una imagen";
	        echo json_encode(  $respuesta  );
	        exit();		
		}
	
}

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{


	$bucket  = 'arkidia';
	$keyname = $_GET['imagen'];

	$s3 = new Aws\S3\S3Client([
		'region'      => 'us-east-1',
		'version'     => 'latest',
		'credentials' => [
			'key'     => 'AKIAVBAXSKP7LE3ABCVV',
			'secret'  => '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',
		]
	]);		

	try {
	    // Get de objeto.
	    $result = $s3->getObject([
	        'Bucket'          => $bucket,
	        'Key'             => $keyname,	        
	    ]);

	    // Display the object in the browser.
	    //header("Content-Type: {$result['ContentType']}");
	    
	    //echo json_encode(var_dump($result));
	    //exit();
	    $body = $result->get('Body');
	    $ruta=$result['@metadata']['effectiveUri'];
	    //$body->rewind();
	    //$content = $body->read($result['contentLength']);
	    //echo $result['Body'];
		echo $result['@metadata']['effectiveUri'] ."<br><br>";//$result['Body'];
		echo '<img src="<?php $ruta; ?>" width="256" height="200" />';
		//echo json_encode($respuesta);
		header("HTTP/1.1 200 OK");
		exit();	
	} catch (S3Exception $e) {
	    echo $e->getMessage() . PHP_EOL;
	}
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>