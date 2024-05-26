<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Obtener datos del cuerpo de la solicitud POST
$json = file_get_contents('php://input');
$params = json_decode($json);

// Verificar si los datos recibidos son válidos
if (!isset($params->nombre) || !isset($params->apellido) || !isset($params->email) || !isset($params->contrasena) || !isset($params->rol_id) ) {
    $response = array(
        "success" => false,
        "message" => "Error, se requieren nombre, apellido, email, contraseña y rol"
    );
    echo json_encode($response);
    exit;
}

// Incluir archivo de conexión
require("../conexion.php");

// Crear instancia de conexión
$conn = new Conexion();
$db = $conn->getConn();

// Obtener datos de usuario del cuerpo de la solicitud
$nombre = htmlspecialchars(strip_tags($params->nombre));
$apellido = htmlspecialchars(strip_tags($params->apellido));
$email = htmlspecialchars(strip_tags($params->email));
$contrasena = htmlspecialchars(strip_tags($params->contrasena));
$rol_id = htmlspecialchars(strip_tags($params->rol_id));

// Verificar si el email ya está registrado
$sql_verificar = "SELECT COUNT(*) AS total FROM USUARIOS WHERE email = :email";
$stmt_verificar = $db->prepare($sql_verificar);
$stmt_verificar->bindParam(':email', $email);
$stmt_verificar->execute();
$total_registros = $stmt_verificar->fetchColumn();

if ($total_registros > 0) {
    $response = array(
        "success" => false,
        "message" => "Error, el email ya está registrado"
    );
    echo json_encode($response);
    exit;
}

// Insertar nuevo usuario
$sql_insertar = "INSERT INTO USUARIOS (nombre, apellido, email, contraseña, rol_id) VALUES (:nombre, :apellido, :email, :contrasena, :rol_id)";
$stmt_insertar = $db->prepare($sql_insertar);

$stmt_insertar->bindParam(':nombre', $nombre);
$stmt_insertar->bindParam(':apellido', $apellido);
$stmt_insertar->bindParam(':email', $email);
$stmt_insertar->bindParam(':contrasena', $contrasena);
$stmt_insertar->bindParam(':rol_id', $rol_id);

$stmt_insertar->execute();

// Verificar si el registro fue exitoso
if ($stmt_insertar->rowCount() > 0) {
    $response = array(
        "success" => true,
        "message" => "Usuario registrado correctamente"
    );
} else {
    $response = array(
        "success" => false,
        "message" => "Error al registrar usuario"
    );
}

// Devolver respuesta en formato JSON
echo json_encode($response);
?>
