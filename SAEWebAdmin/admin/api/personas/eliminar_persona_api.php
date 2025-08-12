<?php
require_once '../../../config/db_connection.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && isset($_GET['dni'], $_GET['rol'])) {
    header('Content-Type: application/json');

    $dni = $_GET['dni'];
    $rol = $_GET['rol'];

    if (!is_numeric($dni) || !is_numeric($rol)) {
        echo json_encode(["exito" => false, "mensaje" => "Parámetros inválidos."]);
        exit();
    }

    $queryCheckMaterias = "SELECT COUNT(*) as total FROM personas_carreras_materias WHERE DNI_PERSONA = ?";
    $stmtCheckMaterias = $conn->prepare($queryCheckMaterias);
    $stmtCheckMaterias->bind_param("i", $dni);
    $stmtCheckMaterias->execute();
    $stmtCheckMaterias->bind_result($totalMaterias);
    $stmtCheckMaterias->fetch();
    $stmtCheckMaterias->close();

    if ($totalMaterias > 0) {
        if ($rol == 1) {
            $mensaje = "No se puede eliminar esta persona porque tiene carreras asignadas.";
        } else {
            $mensaje = "No se puede eliminar esta persona porque tiene materias asignadas.";
        }
        echo json_encode(["exito" => false, "mensaje" => $mensaje]);
        exit();
    }

    echo json_encode(["exito" => true]);
    exit();
}

if ($method === 'DELETE') {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['dni']) ) {
        echo json_encode(["exito" => false, "mensaje" => "DNI no proporcionado."]);
        exit();
    }

    $dni = $input['dni'];
    $rol = $input['rol'];

    // Verificar si la persona tiene materias asignadas antes de eliminar
    $queryCheckMaterias = "SELECT COUNT(*) as total FROM personas_carreras_materias WHERE DNI_PERSONA = ?";
    $stmtCheckMaterias = $conn->prepare($queryCheckMaterias);
    $stmtCheckMaterias->bind_param("i", $dni);
    $stmtCheckMaterias->execute();
    $stmtCheckMaterias->bind_result($totalMaterias);
    $stmtCheckMaterias->fetch();
    $stmtCheckMaterias->close();

    if ($totalMaterias > 0) {
        if ($rol == 1) {
            $mensaje = "No se puede eliminar esta persona porque tiene carreras asignadas.";
        } else {
            $mensaje = "No se puede eliminar esta persona porque tiene materias asignadas.";
        }
        echo json_encode(["exito" => false, "mensaje" => $mensaje]);
        exit();
    }

    // Eliminar de la tabla usuarios si existe
    $queryDeleteUsuario = "DELETE FROM usuarios WHERE DNI_PERSONA = ?";
    $stmtDeleteUsuario = $conn->prepare($queryDeleteUsuario);
    $stmtDeleteUsuario->bind_param("i", $dni);
    $stmtDeleteUsuario->execute();
    $stmtDeleteUsuario->close();

    // Eliminar de la tabla personas
    $queryDeletePersona = "DELETE FROM personas WHERE DNI_PERSONA = ?";
    $stmtDeletePersona = $conn->prepare($queryDeletePersona);
    $stmtDeletePersona->bind_param("i", $dni);
    
    if ($stmtDeletePersona->execute()) {
        echo json_encode(["exito" => true, "mensaje" => "Persona eliminada correctamente."]);
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Error al eliminar la persona."]);
    }

    $stmtDeletePersona->close();
    exit();
}

echo json_encode(["exito" => false, "mensaje" => "Método no permitido."]);
?>
