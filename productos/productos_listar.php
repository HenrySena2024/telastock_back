<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require("../conexion.php");

$conn = new Conexion();
$db = $conn->getConn();

$sql = "select p.producto_id, p.nombre,p.descripcion,p.precio,c.nombre as familia from productos as p 
inner join categorias as c on p.categoria_id = c.categoria_id;";
$stmt = $db->prepare($sql);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($resultado) {
    $response = $resultado;
} else {
    $response = "Error";
}

echo json_encode($response);
?>