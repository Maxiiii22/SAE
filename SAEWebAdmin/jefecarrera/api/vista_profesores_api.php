<?php
require_once '../../config/db_connection.php';
header('Content-Type: application/json; charset=utf-8');

// Iniciar sesión
session_start();

// Verificar que el DNI de la persona está en la sesión
if (!isset($_SESSION['dni_persona'])) {
    echo json_encode(['status' => 'error', 'message' => 'DNI no encontrado en la sesión.']);
    exit();
}

$dniPersona = $_SESSION['dni_persona'];

// Obtener los usuarios con rol 2, que están en las mismas carreras que el usuario con el DNI proporcionado, pero excluyendo al mismo usuario
$sql = "
SELECT u.DNI_PERSONA, p.NOMBRE_PERSONA, p.APELLIDO_PERSONA, u.MAIL_USUARIO, 
       GROUP_CONCAT(DISTINCT c.DESCRIPCION ORDER BY c.DESCRIPCION SEPARATOR '<br>') AS CARRERAS,
       GROUP_CONCAT(
           CONCAT(
               m.NOMBRE_MATERIA COLLATE utf8_general_ci, ' – ',
               cm.DESCRIPCION COLLATE utf8_general_ci, ' - Turno ', t.DESCRIPCION COLLATE utf8_general_ci, 
               ' (<b>Carrera:</b> ', c.TITULO_ABREVIADO, ')'
           ) SEPARATOR '<br>' 
       ) AS MATERIAS_COMPLETAS
FROM usuarios u 
JOIN personas p ON u.DNI_PERSONA = p.DNI_PERSONA
JOIN personas_carreras_materias pcm ON u.DNI_PERSONA = pcm.DNI_PERSONA
JOIN materias m ON pcm.CODIGO_MATERIA = m.CODIGO_MATERIA
JOIN comisiones cm ON pcm.CODIGO_COMISION = cm.CODIGO_COMISION
JOIN horarios h ON pcm.CODIGO_HORARIO = h.CODIGO_HORARIO
JOIN turnos t ON h.CODIGO_TURNO = t.CODIGO_TURNO
JOIN carreras c ON pcm.ID_CARRERA = c.ID_CARRERA
WHERE p.CODIGO_ROL = 2 
  AND pcm.ID_CARRERA IN (  -- Obtener las carreras del usuario con el DNI proporcionado
        SELECT ID_CARRERA 
        FROM personas_carreras_materias 
        WHERE DNI_PERSONA = ?  -- El DNI del usuario cuyo dato se pasa
      ) 
  AND u.DNI_PERSONA != ?  -- Excluir al usuario cuyo DNI se pasa
GROUP BY u.DNI_PERSONA;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $dniPersona, $dniPersona);  // Pasamos ambos parámetros como enteros
$stmt->execute();
$result = $stmt->get_result();
$profesores = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($profesores) {
    echo json_encode(['status' => 'success', 'profesores' => $profesores]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se encontraron profesores.']);
}
?>
