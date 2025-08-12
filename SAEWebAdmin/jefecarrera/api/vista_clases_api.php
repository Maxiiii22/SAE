<?php
require_once '../../config/db_connection.php';
header('Content-Type: application/json; charset=utf-8');

// Obtener parámetros de la URL
$codigoMateria = $_GET['codigo_materia'] ?? null;
$comision = $_GET['comision'] ?? null;
$horario = $_GET['horario'] ?? null;

if (!$codigoMateria || !$comision || !$horario) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros obligatorios.']);
    exit();
}

// 🔹 Obtener información de la materia, comisión y horario 
$sqlInfo = "
    SELECT 
        m.NOMBRE_MATERIA, 
        c.DESCRIPCION AS COMISION_DESCRIPCION, 
        h.HORARIO AS HORARIO_DESCRIPCION
    FROM materias m
    INNER JOIN comisiones c ON c.CODIGO_COMISION = ?
    INNER JOIN horarios h ON h.CODIGO_HORARIO = ?
    WHERE m.CODIGO_MATERIA = ?
";

$stmtInfo = $conn->prepare($sqlInfo);
$stmtInfo->bind_param('iii', $comision, $horario, $codigoMateria);
$stmtInfo->execute();
$resultInfo = $stmtInfo->get_result();
$info = $resultInfo->fetch_assoc();

$nombreMateria = $info['NOMBRE_MATERIA'] ?? 'Desconocido';
$descripcionComision = $info['COMISION_DESCRIPCION'] ?? 'Desconocido';
$descripcionHorario = $info['HORARIO_DESCRIPCION'] ?? 'Desconocido';

// 🔹 Obtener el profesor asignado a la materia, comisión y horario
$sqlProfesor = "
    SELECT p.DNI_PERSONA, p.NOMBRE_PERSONA, p.APELLIDO_PERSONA
    FROM personas p
    INNER JOIN personas_carreras_materias pcm ON p.DNI_PERSONA = pcm.DNI_PERSONA
    WHERE pcm.CODIGO_MATERIA = ? AND pcm.CODIGO_COMISION = ? AND pcm.CODIGO_HORARIO = ?
    LIMIT 1
";

$stmtProfesor = $conn->prepare($sqlProfesor);
$stmtProfesor->bind_param('iii', $codigoMateria, $comision, $horario);
$stmtProfesor->execute();
$resultProfesor = $stmtProfesor->get_result();
$profesor = $resultProfesor->fetch_assoc();

$nombreProfesor = $profesor ? $profesor['NOMBRE_PERSONA'] . ' ' . $profesor['APELLIDO_PERSONA'] : 'No asignado';
$dniProfesor = $profesor['DNI_PERSONA'] ?? null;

// 🔹 Obtener el código de usuario del profesor en la tabla `usuarios`
$codigoUsuario = null;
if ($dniProfesor) {
    $sqlUsuario = "SELECT CODIGO_USUARIO FROM usuarios WHERE DNI_PERSONA = ?";
    $stmtUsuario = $conn->prepare($sqlUsuario);
    $stmtUsuario->bind_param('i', $dniProfesor);
    $stmtUsuario->execute();
    $resultUsuario = $stmtUsuario->get_result();
    $usuario = $resultUsuario->fetch_assoc();
    $codigoUsuario = $usuario['CODIGO_USUARIO'] ?? null;
}

// 🔹 Obtener las clases solo si el usuario existe
$clases = [];
if ($codigoUsuario) {
    $sqlClases = "
        SELECT c.ID_CLASE, c.FECHA, c.HORA_INICIO, c.HORA_FIN, c.TEMAS, c.NOVEDADES, c.AULA, c.ARCHIVOS
        FROM clases c
        WHERE c.CODIGO_USUARIO = ? AND c.CODIGO_MATERIA = ? AND c.CODIGO_COMISION = ? AND c.CODIGO_HORARIO = ?
    ";
    $stmtClases = $conn->prepare($sqlClases);
    $stmtClases->bind_param('iiii', $codigoUsuario, $codigoMateria, $comision, $horario);
    $stmtClases->execute();
    $resultClases = $stmtClases->get_result();
    $clases = $resultClases->fetch_all(MYSQLI_ASSOC);
}

// 🔹 Respuesta JSON
echo json_encode([
    'status' => 'success',
    'nombre_materia' => $nombreMateria,
    'comision_descripcion' => $descripcionComision,
    'horario_descripcion' => $descripcionHorario,
    'nombre_profesor' => $nombreProfesor,
    'clases' => $clases
]);

// 🔹 Cerrar conexiones
$stmtInfo->close();
$stmtProfesor->close();
if (isset($stmtUsuario)) $stmtUsuario->close();
if (isset($stmtClases)) $stmtClases->close();
$conn->close();
?>
