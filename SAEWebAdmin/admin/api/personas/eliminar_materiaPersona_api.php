<?php
require_once '../../../config/db_connection.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'DELETE') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['rol'], $input['dni'], $input['carrera'])) {
        echo json_encode(["exito" => false, "mensaje" => "Faltan datos obligatorios (rol, dni o carrera)."]);
        exit();
    }

    $rol = intval($input['rol']);
    $dni = intval($input['dni']);
    $carrera = intval($input['carrera']);

    if ($rol === 1) {
        // ✅ Jefe de Área → Desasignar solo la carrera (otros campos son NULL)
        $query = "DELETE FROM personas_carreras_materias 
                  WHERE DNI_PERSONA = ? AND ID_CARRERA = ? 
                    AND CODIGO_MATERIA IS NULL AND CODIGO_COMISION IS NULL AND CODIGO_HORARIO IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $dni, $carrera);

        if ($stmt->execute()) {
            echo json_encode(["exito" => true, "mensaje" => "Carrera desasignada correctamente."]);
        } else {
            echo json_encode(["exito" => false, "mensaje" => "Error al desasignar la carrera."]);
        }

        $stmt->close();
        exit();
    }

    // ✅ Docente → debe tener todos los datos
    if (!isset($input['materia'], $input['comision'], $input['horario'])) {
        echo json_encode(["exito" => false, "mensaje" => "Faltan datos para desasignar la materia."]);
        exit();
    }

    $materia = intval($input['materia']);
    $comision = intval($input['comision']);
    $horario = intval($input['horario']);

    $query = "DELETE FROM personas_carreras_materias 
              WHERE DNI_PERSONA = ? AND ID_CARRERA = ? 
                AND CODIGO_MATERIA = ? AND CODIGO_COMISION = ? AND CODIGO_HORARIO = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiii", $dni, $carrera, $materia, $comision, $horario);

    if ($stmt->execute()) {
        echo json_encode(["exito" => true, "mensaje" => "Materia desasignada correctamente."]);
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Error al desasignar la materia."]);
    }

    $stmt->close();
    exit();
}

// ❌ Si no es DELETE
echo json_encode(["exito" => false, "mensaje" => "Método no permitido."]);
?>
