<?php
require_once '../../config/db_connection.php';
header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION['dni_persona'])) {
    echo json_encode(['status' => 'error', 'message' => 'DNI no encontrado en la sesión.']);
    exit();
}

$dniPersona = $_SESSION['dni_persona'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $diasSemana = ['Martes', 'Miércoles', 'Jueves', 'Viernes'];
    $asignados = [];

    // Consulta para obtener todas las carreras asociadas al usuario
    $sqlCarrera = "
        SELECT c.ID_CARRERA, c.TITULO_ABREVIADO, c.DESCRIPCION
        FROM personas_carreras_materias pcm
        INNER JOIN carreras c ON pcm.ID_CARRERA = c.ID_CARRERA
        WHERE pcm.DNI_PERSONA = ?
        ORDER BY c.ID_CARRERA"; // Ordenamos para que la primera carrera sea la de menor ID

    $stmtCarrera = $conn->prepare($sqlCarrera);
    $stmtCarrera->bind_param("i", $dniPersona);
    $stmtCarrera->execute();
    $result = $stmtCarrera->get_result();
    $carreras = [];

    if ($result->num_rows > 0) {
        while ($carrera = $result->fetch_assoc()) {
            $carreras[] = $carrera;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se encontraron carreras asociadas.']);
        exit();
    }

    // Obtenemos el ID de la primera carrera (puede ser la de menor ID)
    $idCarreraPredeterminada = $carreras[0]['ID_CARRERA'];

    // Obtener las materias de la carrera predeterminada
    $sqlMaterias = "
        SELECT pcm.CODIGO_MATERIA, p.DNI_PERSONA, p.NOMBRE_PERSONA, p.APELLIDO_PERSONA, m.NOMBRE_MATERIA, cm.CODIGO_COMISION, cm.DESCRIPCION AS COMISION, 
               h.CODIGO_HORARIO, h.HORARIO, t.DESCRIPCION AS TURNO, 
               CASE 
                   WHEN (m.CODIGO_MATERIA >= 11000 AND m.CODIGO_MATERIA < 12000) OR (m.CODIGO_MATERIA >= 21000 AND m.CODIGO_MATERIA < 22000)  THEN '1°'
                   WHEN (m.CODIGO_MATERIA >= 12000 AND m.CODIGO_MATERIA < 13000) OR (m.CODIGO_MATERIA >= 22000 AND m.CODIGO_MATERIA < 23000)  THEN '2°'
                   WHEN (m.CODIGO_MATERIA >= 13000 AND m.CODIGO_MATERIA < 14000) OR (m.CODIGO_MATERIA >= 23000 AND m.CODIGO_MATERIA < 24000)  THEN '3°'
                   ELSE 'Ajustar año en jefe_api.php'
               END AS ANIO,
               pcm.ID_CARRERA  
        FROM personas_carreras_materias pcm
        INNER JOIN personas p ON pcm.DNI_PERSONA = p.DNI_PERSONA
        INNER JOIN materias m ON pcm.CODIGO_MATERIA = m.CODIGO_MATERIA
        INNER JOIN comisiones cm ON pcm.CODIGO_COMISION = cm.CODIGO_COMISION
        INNER JOIN horarios h ON pcm.CODIGO_HORARIO = h.CODIGO_HORARIO
        INNER JOIN turnos t ON h.CODIGO_TURNO = t.CODIGO_TURNO
        WHERE pcm.DNI_PERSONA NOT IN (?)";

    $stmtMaterias = $conn->prepare($sqlMaterias);
    $stmtMaterias->bind_param("i", $dniPersona);
    $stmtMaterias->execute();
    $materiasTodas = $stmtMaterias->get_result()->fetch_all(MYSQLI_ASSOC);

    // Asignación de días a las materias de la carrera predeterminada
    foreach ($materiasTodas as &$materia) {
        $clave = $materia['COMISION'] . $materia['HORARIO'] . $materia['TURNO'];
        if (!isset($asignados[$clave])) {
            $asignados[$clave] = [];
        }

        if (in_array($materia['CODIGO_HORARIO'], [1, 2, 5, 6])) {
            $materia['DIA'] = 'Lunes';
        } else {
            $disponibles = array_diff($diasSemana, $asignados[$clave]);
            if (empty($disponibles)) {
                $materia['DIA'] = $diasSemana[array_rand($diasSemana)];
            } else {
                $materia['DIA'] = array_shift($disponibles);
            }
        }

        $asignados[$clave][] = $materia['DIA'];
    }

    $stmtMaterias->close();
    $stmtCarrera->close();


    // Devolvemos la respuesta con todas las carreras y las materias asignadas
    echo json_encode([
        'status' => 'success',
        'carreras' => $carreras, // Todas las carreras
        'materias' => $materiasTodas 
    ]);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Método no válido.']);
exit();
