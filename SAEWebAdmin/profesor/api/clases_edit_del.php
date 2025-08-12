<?php
require_once '../../config/db_connection.php';
header('Content-Type: application/json; charset=utf-8');

// Acción: Obtener detalles de una clase
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtener_clase') {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM clases WHERE ID_CLASE = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result) {
        echo json_encode(['status' => 'success', 'clase' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Clase no encontrada.']);
    }
    exit();
}

// Acción: Editar clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'editar') {


    $id = intval($_POST['id']);
    $fecha = $_POST['fechaClase'];
    $horaInicio = $_POST['horaInicio'];
    $horaFin = $_POST['horaFin'];
    $temas = $_POST['temasClase'];
    $novedades = $_POST['novedadesClase'];
    $aula = $_POST['aulaClase'];
    $archivoNombre = $_FILES['archivosClase']['name'] ?? null;

    if ($archivoNombre) {
        $uploadDir = '../../assets/docs/';
        $uploadFile = $uploadDir . basename($archivoNombre);
        if (!move_uploaded_file($_FILES['archivosClase']['tmp_name'], $uploadFile)) {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo.']);
            exit();
        }
    }

    $sql = "UPDATE clases SET FECHA = ?, HORA_INICIO = ?, HORA_FIN = ?, TEMAS = ?, NOVEDADES = ?, AULA = ?, ARCHIVOS = ? WHERE ID_CLASE = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $fecha, $horaInicio, $horaFin, $temas, $novedades, $aula, $archivoNombre, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Clase actualizada.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la clase.']);
    }
    exit();
}

// Acción: Eliminar clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminar') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id']);

    $sql = "DELETE FROM clases WHERE ID_CLASE = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Clase eliminada.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la clase.']);
    }
    exit();
}


echo json_encode(['status' => 'error', 'message' => 'Acción no válida.']);
exit();
