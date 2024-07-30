<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Obtener datos del cuerpo de la solicitud PUT
$json = file_get_contents('php://input');
$params = json_decode($json);

// Verificar si los datos recibidos son válidos
if (!isset($params->producto_id) || !isset($params->nombre) || !isset($params->descripcion) || !isset($params->categoria_id) || !isset($params->precio) ) {
    $response = array(
        "success" => false,
        "message" => "Error, se requiere producto_id, nombre, descripcion, categoria_id, precio"
    );
    echo json_encode($response);
    exit;
}

// Incluir archivo de conexión
require("../conexion.php");

// Crear instancia de conexión
$conn = new Conexion();
$db = $conn->getConn();

// Obtener datos del usuario del cuerpo de la solicitud
$producto_id = htmlspecialchars(strip_tags($params->producto_id));
$nombre = htmlspecialchars(strip_tags($params->nombre));
$descripcion = htmlspecialchars(strip_tags($params->descripcion));
$categoria_id = htmlspecialchars(strip_tags($params->categoria_id));
$precio = htmlspecialchars(strip_tags($params->precio));


// Verificar si el usuario existe
$sql_verificar = "SELECT COUNT(*) AS total FROM productos WHERE producto_id = :producto_id";
$stmt_verificar = $db->prepare($sql_verificar);
$stmt_verificar->bindParam(':producto_id', $producto_id);
$stmt_verificar->execute();
$total_registros = $stmt_verificar->fetchColumn();

if ($total_registros == 0) {
    $response = array(
        "success" => false,
        "message" => "Error, el producto  no existe"
    );
    echo json_encode($response);
    exit;
}

// Actualizar datos del usuario
$sql_actualizar = "UPDATE productos SET producto_id = :producto_id, nombre = :nombre, descripcion = :descripcion, categoria_id = :categoria_id, precio = :precio WHERE producto_id = :producto_id";
$stmt_actualizar = $db->prepare($sql_actualizar);

$stmt_actualizar->bindParam(':producto_id', $producto_id);
$stmt_actualizar->bindParam(':nombre', $nombre);
$stmt_actualizar->bindParam(':descripcion', $descripcion);
$stmt_actualizar->bindParam(':categoria_id', $categoria_id);
$stmt_actualizar->bindParam(':precio', $precio);


$stmt_actualizar->execute();

// Verificar si la actualización fue exitosa
if ($stmt_actualizar->rowCount() > 0) {
    $response = array(
        "success" => true,
        "message" => "Producto  actualizado correctamente"
    );
} else {
    $response = array(
        "success" => false,
        "message" => "Error al actualizar producto"
    );
}

// Devolver respuesta en formato JSON
echo json_encode($response);
?>