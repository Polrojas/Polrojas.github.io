<?php
include "config.php";
include "utils.php";
include "SegCla.php";
$dbConn =  connect($db);
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $pass = $_POST['pass'];   
    $password = encriptar($pass);
    //Se busca si el usuario es un padre
    $sql = $dbConn->prepare("SELECT * FROM usuario_padre where mail=:mail");
    $sql->bindParam(':mail', $email);    
    $sql->execute();
    $fila_padre = $sql->fetch(PDO::FETCH_ASSOC);

    //Se busca si el usuario es un administrador
    $sql = $dbConn->prepare("SELECT * FROM usuario_administrador where mail=:mail");
    $sql->bindParam(':mail', $email);    
    $sql->execute();
    $fila_administrador= $sql->fetch(PDO::FETCH_ASSOC);

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha_formateada = date("Y-m-d H:i:s",time());
    $clave = claveAleatoria();
    $clave_string="";
    foreach($clave as $row)
    {
        $clave_string .= $row;
    }
    $ind_uso = 0;    
    if ($fila_padre){         
        $sql = "INSERT INTO cambio_clave
            (fecha, usuario, password, clave, ind_uso)
            VALUES
            (:fecha, :usuario, :password, :clave, :ind_uso)";        
        $statement = $dbConn->prepare($sql);          
        $statement->bindParam(':fecha', $fecha_formateada);
        $statement->bindParam(':usuario', $email);
        $statement->bindParam(':password', $password);
        $statement->bindParam(':clave', $clave_string);   
        $statement->bindParam(':ind_uso', $ind_uso);
        $statement->execute();
        if($statement){
            enviaClave($email, $fila_padre['nombre'], $clave_string);
            echo "success";
        }else{
            echo "fail";
        }

    }    
 
}else{
    echo "No se por qué salió por acá?";
    header("location:../index.html");
}

?>