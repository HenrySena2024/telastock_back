<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Obtener datos del cuerpo de la solicitud DELETE
$json = file_get_contents('php://input');
$params = json_decode($json);

// Verificar si los datos recibidos son válidos
if (!isset($params->id)) {
    $response = array(
        "success" => false,
        "message" => "Error, se requiere el ID del usuario"
    );
    echo json_encode($response);
    exit;
}

// Incluir archivo de conexión
require("../conexion.php");

// Crear instancia de conexión
$conn = new Conexion();
$db = $conn->getConn();

// Obtener ID del usuario del cuerpo de la solicitud
$id = htmlspecialchars(strip_tags($params->id));

// Verificar si el usuario existe
$sql_verificar = "SELECT COUNT(*) AS total FROM USUARIOS WHERE id = :id";
$stmt_verificar = $db->prepare($sql_verificar);
$stmt_verificar->bindParam(':id', $id);
$stmt_verificar->execute();
$total_registros = $stmt_verificar->fetchColumn();

if ($total_registros == 0) {
    $response = array(
        "success" => false,
        "message" => "Error, el usuario no existe"
    );
    echo json_encode($response);
    exit;
}

// Eliminar usuario
$sql_eliminar = "DELETE FROM USUARIOS WHERE id = :id";
$stmt_eliminar = $db->prepare($sql_eliminar);
$stmt_eliminar->bindParam(':id', $id);
$stmt_eliminar->execute();

// Verificar si la eliminación fue exitosa
if ($stmt_eliminar->rowCount() > 0) {
    $response = array(
        "success" => true,
        "message" => "Usuario eliminado correctamente"
    );
} else {
    $response = array(
        "success" => false,
        "message" => "Error al eliminar usuario"
    );
}

// Devolver respuesta en formato JSON
echo json_encode($response);
?>
