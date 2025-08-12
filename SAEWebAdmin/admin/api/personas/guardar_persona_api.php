<?php
require_once '../../../config/db_connection.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['dni'])) {
    echo json_encode(["exito" => false, "mensaje" => "Datos inválidos"]);
    exit();
}

$dni = $input['dni'];
$nombre = $input['nombre'];
$apellido = $input['apellido'];
$codigo_rol = $input['codigo_rol'];
$mail_usuario = isset($input['mail_usuario']) ? trim($input['mail_usuario']) : null;
$contrasena_usuario = isset($input['contrasena_usuario']) ? trim($input['contrasena_usuario']) : null;
$materias = $input['materias'] ?? [];
$carreras = $input['carreras'] ?? [];

if (!in_array($codigo_rol, [1, 2])) {
    echo json_encode(["exito" => false, "mensaje" => "Rol no válido"]);
    exit();
}

$conn->begin_transaction();

try {
    // **Actualizar la tabla personas**
    $queryPersona = "UPDATE personas SET NOMBRE_PERSONA = ?, APELLIDO_PERSONA = ?, CODIGO_ROL = ? WHERE DNI_PERSONA = ?";
    $stmt = $conn->prepare($queryPersona);
    if (!$stmt) {
        throw new Exception("Error preparando la consulta de actualización de personas: " . $conn->error);
    }
    $stmt->bind_param("ssii", $nombre, $apellido, $codigo_rol, $dni);
    if (!$stmt->execute()) {
        throw new Exception("Error al actualizar personas: " . $stmt->error);
    }

    // **Verificar si el usuario está registrado en la tabla usuarios**
    $queryCheckUsuario = "SELECT MAIL_USUARIO, CONTRASEÑA_USUARIO FROM usuarios WHERE DNI_PERSONA = ?";
    $stmt = $conn->prepare($queryCheckUsuario);
    if (!$stmt) {
        throw new Exception("Error preparando la consulta de verificación de usuario: " . $conn->error);
    }
    $stmt->bind_param("i", $dni);
    if (!$stmt->execute()) {
        throw new Exception("Error al verificar usuario: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();

    $usuarioExiste = $usuario ? true : false;

    // **Si el usuario existe, actualizar solo si hay cambios**
    if ($usuarioExiste) {
        if ($usuario['MAIL_USUARIO'] !== $mail_usuario || $usuario['CONTRASEÑA_USUARIO'] !== $contrasena_usuario) {
            $queryActualizarUsuario = "UPDATE usuarios SET MAIL_USUARIO = ?, CONTRASEÑA_USUARIO = ? WHERE DNI_PERSONA = ?";
            $stmt = $conn->prepare($queryActualizarUsuario);
            if (!$stmt) {
                throw new Exception("Error preparando la consulta de actualización de usuario: " . $conn->error);
            }
            $stmt->bind_param("ssi", $mail_usuario, $contrasena_usuario, $dni);
            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar usuario: " . $stmt->error);
            }
        }
    }

    // **Actualizar la tabla personas_carreras_materias**
    if ($codigo_rol == 1) { // Jefe de área: eliminar solo carreras sin materias asociadas
        $queryEliminarCarreras = "DELETE FROM personas_carreras_materias 
                                  WHERE DNI_PERSONA = ? AND 
                                        CODIGO_MATERIA IS NULL AND 
                                        CODIGO_COMISION IS NULL AND 
                                        CODIGO_HORARIO IS NULL";
        $stmt = $conn->prepare($queryEliminarCarreras);
        if (!$stmt) {
            throw new Exception("Error preparando eliminación de carreras: " . $conn->error);
        }
        $stmt->bind_param("i", $dni);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar carreras: " . $stmt->error);
        }
    
        if (!empty($carreras)) {
            $queryInsertCarrera = "INSERT INTO personas_carreras_materias (DNI_PERSONA, ID_CARRERA)
                                   VALUES (?, ?)";
            $stmt = $conn->prepare($queryInsertCarrera);
            if (!$stmt) {
                throw new Exception("Error preparando inserción de carreras: " . $conn->error);
            }
    
            foreach ($carreras as $carrera) {
                $stmt->bind_param("ii", $dni, $carrera['id_carrera']);
                if (!$stmt->execute()) {
                    throw new Exception("Error al insertar carrera: " . $stmt->error);
                }
            }
        }
    
    } elseif ($codigo_rol == 2) { // Profe: eliminar todas las asignaciones y guardar materias completas
        $queryEliminarMaterias = "DELETE FROM personas_carreras_materias WHERE DNI_PERSONA = ?";
        $stmt = $conn->prepare($queryEliminarMaterias);
        if (!$stmt) {
            throw new Exception("Error preparando eliminación de materias: " . $conn->error);
        }
        $stmt->bind_param("i", $dni);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar materias: " . $stmt->error);
        }
    
        if (!empty($materias)) {
            $queryInsertMaterias = "INSERT INTO personas_carreras_materias (DNI_PERSONA, ID_CARRERA, CODIGO_MATERIA, CODIGO_COMISION, CODIGO_HORARIO)
                                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($queryInsertMaterias);
            if (!$stmt) {
                throw new Exception("Error preparando inserción de materias: " . $conn->error);
            }
            foreach ($materias as $materia) {
                $stmt->bind_param("iiiii", $dni, $materia['id_carrera'], $materia['codigo_materia'], $materia['codigo_comision'], $materia['codigo_horario']);
                if (!$stmt->execute()) {
                    throw new Exception("Error al insertar materia: " . $stmt->error);
                }
            }
        }
    }

    $conn->commit();
    echo json_encode(["exito" => true, "mensaje" => "Datos actualizados correctamente"]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["exito" => false, "mensaje" => "Error al actualizar: " . $e->getMessage()]);
}
?>
