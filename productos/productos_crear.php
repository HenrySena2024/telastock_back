<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Obtener datos del cuerpo de la solicitud POST
$json = file_get_contents('php://input');
$params = json_decode($json);

// Verificar si los datos recibidos son válidos
if (!isset($params->nombre) || !isset($params->descripcion) || !isset($params->categoria_id) || !isset($params->precio) ) {
    $response = array(
        "success" => false,
        "message" => "Error, se requieren nombre, descripcion, categoria_id , precio"
    );
    echo json_encode($response);
    exit;
}
require("../conexion.php");

$conn = new Conexion();
$db = $conn->getConn();


// Obtener datos de usuario del cuerpo de la solicitud
$nombre = htmlspecialchars(strip_tags($params->nombre));
$descripcion = htmlspecialchars(strip_tags($params->descripcion));
$categoria_id = htmlspecialchars(strip_tags($params->categoria_id));
$precio = htmlspecialchars(strip_tags($params->precio));

// Insertar nuevo usuario
$sql_insertar = "insert into productos (nombre, descripcion,categoria_id,precio)values (:nombre, :descripcion,:categoria_id,:precio)";
$stmt_insertar = $db->prepare($sql_insertar);

$stmt_insertar->bindParam(':nombre', $nombre);
$stmt_insertar->bindParam(':descripcion', $descripcion);
$stmt_insertar->bindParam(':categoria_id', $categoria_id);
$stmt_insertar->bindParam(':precio', $precio);


$stmt_insertar->execute();


// Verificar si el registro fue exitoso
if ($stmt_insertar->rowCount() > 0) {
    $response = array(
        "success" => true,
        "message" => "Producto creado exitoso"
    );
} else {
    $response = array(
        "success" => false,
        "message" => "Error al crear producto"
    );
}

// Devolver respuesta en formato JSON
echo json_encode($response);
?>

