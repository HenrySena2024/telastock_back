<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$json = file_get_contents('php://input'); 
$params = json_decode($json); 

require("../conexion.php");

$conn = new conexion();

$db = $conn->getConn();

$email = htmlspecialchars(strip_tags($params->email));
$contrasena = htmlspecialchars(strip_tags($params->contrasena));

$sql = "SELECT * FROM USUARIOS WHERE email = :email AND contraseña = :contrasena";

$stmt = $db->prepare($sql);

$stmt->bindParam(':email', $email);
$stmt->bindParam(':contrasena', $contrasena);

$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

class Result
{
  public $resultado;
  public $mensaje;
}

$response = new Result();

if (count($resultado) > 0) {
  $response->resultado = true;
  $response->mensaje = $resultado;
} else {
  $response->resultado = false;
  $response->mensaje = 'Error, credenciales incorrectas';
} 

echo json_encode($response);