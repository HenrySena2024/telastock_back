<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Obtener datos del cuerpo de la solicitud PUT
$json = file_get_contents('php://input');
$params = json_decode($json);

// Verificar si los datos recibidos son válidos
if (!isset($params->id) || !isset($params->nombre) || !isset($params->apellido) || !isset($params->email) || !isset($params->contrasena) || !isset($params->rol_id) ) {
    $response = array(
        "success" => false,
        "message" => "Error, se requiere id, nombre, apellido, email, contraseña y rol"
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
$id = htmlspecialchars(strip_tags($params->id));
$nombre = htmlspecialchars(strip_tags($params->nombre));
$apellido = htmlspecialchars(strip_tags($params->apellido));
$email = htmlspecialchars(strip_tags($params->email));
$contrasena = htmlspecialchars(strip_tags($params->contrasena));
$rol_id = htmlspecialchars(strip_tags($params->rol_id));

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

// Actualizar datos del usuario
$sql_actualizar = "UPDATE USUARIOS SET nombre = :nombre, apellido = :apellido, email = :email, contraseña = :contrasena, rol_id = :rol_id WHERE id = :id";
$stmt_actualizar = $db->prepare($sql_actualizar);

$stmt_actualizar->bindParam(':id', $id);
$stmt_actualizar->bindParam(':nombre', $nombre);
$stmt_actualizar->bindParam(':apellido', $apellido);
$stmt_actualizar->bindParam(':email', $email);
$stmt_actualizar->bindParam(':contrasena', $contrasena);
$stmt_actualizar->bindParam(':rol_id', $rol_id);

$stmt_actualizar->execute();

// Verificar si la actualización fue exitosa
if ($stmt_actualizar->rowCount() > 0) {
    $response = array(
        "success" => true,
        "message" => "Usuario actualizado correctamente"
    );
} else {
    $response = array(
        "success" => false,
        "message" => "Error al actualizar usuario"
    );
}

// Devolver respuesta en formato JSON
echo json_encode($response);
?>
