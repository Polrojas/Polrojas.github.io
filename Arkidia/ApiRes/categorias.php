<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);
/*
  listar todos los posts o solo uno
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['id_categoria']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM categorias where id_categoria=:id_categoria");
      $sql->bindValue(':id_categoria', $_GET['id_categoria']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
    }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM categorias");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
  }
}
// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO categorias
          (descripcion, imagen_categoria)
          VALUES
          (:descripcion, :imagen_categoria)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if($postId)
    {
      $input['id_categoria'] = $postId;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
   }
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
  $id = $_GET['id_categoria'];
  $statement = $dbConn->prepare("DELETE FROM categorias where id_categoria=:id_categoria");
  $statement->bindValue(':id_categoria', $id);
  $statement->execute();
  header("HTTP/1.1 200 OK");
  exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['id_categoria'];
    $fields = getParams($input);
    $sql = "
          UPDATE categorias SET $fields WHERE id_categorias='$postId'
           ";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>
