<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Incluir archivo de conexión
require("../conexion.php");

// Crear instancia de conexión
$conn = new Conexion();
$db = $conn->getConn();

// Consulta SQL para obtener todos los usuarios
$sql = "SELECT U.id, U.nombre, U.apellido, U.email,U.contraseña as contrasena,U.rol_id, R.nombre as rol FROM usuarios AS U
INNER JOIN rol as R ON u.rol_id = R.id";
$stmt = $db->prepare($sql);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se obtuvieron resultados
if ($resultado) {
    $response = $resultado;
} else {
    $response = "Error";
}

// Devolver respuesta en formato JSON
echo json_encode($response);
?>
