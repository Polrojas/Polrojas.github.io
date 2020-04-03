<?php
include "config.php";
include "utils.php";
include "SegCla.php";
$dbConn =  connect($db);
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $clave= $_POST['clave'];    
    //Se busca la clave
    $ind_uso=0;
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha = date("Y-m-d H:i:s",time());    
    
    echo $fecha;
    $sql = $dbConn->prepare("SELECT * FROM cambio_clave 
                            WHERE clave=:clave AND fecha <= :fecha AND ind_uso = :ind_uso");
    $sql->bindParam(':clave', $clave); 
    $sql->bindParam(':fecha', $fecha);
    $sql->bindParam(':ind_uso', $ind_uso);
    $sql->execute();
    $fila = $sql->fetch(PDO::FETCH_ASSOC);

        
    if ($fila){
        //Se busca si el usuario es un padre
        $sql = $dbConn->prepare("SELECT * FROM usuario_padre where mail=:mail");
        $sql->bindParam(':mail', $fila['usuario']);    
        $sql->execute();
        $fila_padre = $sql->fetch(PDO::FETCH_ASSOC);
        //Se busca si el usuario es un administrador
        $sql = $dbConn->prepare("SELECT * FROM usuario_administrador where mail=:mail");
        $sql->bindParam(':mail', $fila['usuario']);    
        $sql->execute();
        $fila_administrador= $sql->fetch(PDO::FETCH_ASSOC);        
        if($fila_padre){
            //Actualiza la clave del usuario
            $data=[     
                'mail'     => $fila['usuario'],
                'password' => $fila['password'],
            ];    
            $sql = "UPDATE usuario_padre    
                    SET password = :password WHERE mail = :mail";
            $statement = $dbConn->prepare($sql);     
            $statement->execute($data);            
        }elseif($fila_administrador){
            //Actualiza la clave del usuario
            $data=[     
                'mail'     => $fila['usuario'],
                'password' => $fila['password'],
            ];    
            $sql = "UPDATE usuario_administrador    
                    SET password = :password WHERE mail = :mail";
            $statement = $dbConn->prepare($sql);     
            $statement->execute($data);                
        }else{
            echo "No se encontró el registro";
            exit();
        }
 
        //Marca como usada la clave que confirma el cambio de clave
        $ind_uso = 1;
        $data=[     
            'usuario'  => $fila['usuario'],
            'password' => $fila['password'],
            'fecha'    => $fila['fecha'],
            'clave'    => $fila['clave'],
            'ind_uso'  => $ind_uso
        ];    
        $sql = "UPDATE cambio_clave    
                SET ind_uso = :ind_uso 
                WHERE usuario = :usuario AND password = :password AND fecha = :fecha
                AND clave = :clave";
        $statement = $dbConn->prepare($sql);     
        $statement->execute($data);  
        if($statement){            
            echo "success";
        }else{
            echo "fail";
        }
    }else{
        echo "Código no disponible, repita el procedimiento.";
    }
 
}else{
    header("location:../index.html");
}

?>