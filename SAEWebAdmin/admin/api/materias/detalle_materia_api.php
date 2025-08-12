<?php
require_once '../../../config/db_connection.php';

// ✅ GET (cargar datos)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['codMateria']) && isset($_GET['fetch'])) {
    header('Content-Type: application/json');
    $codMateria = $_GET['codMateria'];

    $query = "SELECT CODIGO_MATERIA, NOMBRE_MATERIA FROM materias WHERE CODIGO_MATERIA = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $codMateria);
    $stmt->execute();
    $result = $stmt->get_result();
    $materia = $result->fetch_assoc();
    echo json_encode($materia);
    exit;
}

// ✅ POST (guardar datos)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['codMateria'])) {
        echo json_encode(["exito" => false, "mensaje" => "Datos inválidos"]);
        exit();
    }

    $codMateria = $input['codMateria'];
    $nombreMateria = $input['nombreMateria'];

    $conn->begin_transaction();

    try {
        $query = "UPDATE materias SET NOMBRE_MATERIA = ? WHERE CODIGO_MATERIA = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nombreMateria, $codMateria);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        echo json_encode(["exito" => true, "mensaje" => "Materia actualizada correctamente"]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["exito" => false, "mensaje" => "Error al actualizar: " . $e->getMessage()]);
    }

    exit;
}
?>
