<?php
require_once '../../../config/db_connection.php';
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['codCarrera']) && isset($_GET['fetch'])) {
    header('Content-Type: application/json');
    $codCarrera = $_GET['codCarrera'];
    $query = "SELECT ID_CARRERA, TITULO_ABREVIADO, DESCRIPCION FROM carreras WHERE ID_CARRERA = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $codCarrera);
    $stmt->execute();
    $result = $stmt->get_result();
    $carrera = $result->fetch_assoc();
    echo json_encode($carrera);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['codCarrera'])) {
        echo json_encode(["exito" => false, "mensaje" => "Datos inválidos"]);
        exit();
    }

    $codCarrera = $input['codCarrera'];
    $nombreCarrera = $input['nombreCarrera'];
    $abreviacion = $input['abreviacion'];

    $conn->begin_transaction();

    try {
        $query = "UPDATE carreras SET DESCRIPCION = ?, TITULO_ABREVIADO = ? WHERE ID_CARRERA = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nombreCarrera, $abreviacion, $codCarrera);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        echo json_encode(["exito" => true, "mensaje" => "Datos actualizados correctamente"]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["exito" => false, "mensaje" => "Error al actualizar: " . $e->getMessage()]);
    }

    exit;
}
