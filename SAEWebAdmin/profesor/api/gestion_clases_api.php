<?php

require_once '../../config/db_connection.php';
include '../../config/config.php';

header('Content-Type: application/json; charset=utf-8'); // Asegurar que el contenido devuelto sea JSON


    session_start();

    if (!isset($_SESSION['dni_persona'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'DNI no encontrado en la sesión.'
        ]);
        exit();
    }

    $dniProfesor = $_SESSION['dni_persona'];
    try {
    // Consulta SQL
    $sql = "SELECT pcm.CODIGO_MATERIA, m.NOMBRE_MATERIA, c.TITULO_ABREVIADO, cm.DESCRIPCION, h.HORARIO, pcm.CODIGO_HORARIO, pcm.CODIGO_COMISION 
            FROM personas_carreras_materias pcm 
            JOIN materias m ON pcm.CODIGO_MATERIA = m.CODIGO_MATERIA 
            JOIN carreras c ON pcm.ID_CARRERA = c.ID_CARRERA 
            LEFT JOIN comisiones cm ON pcm.CODIGO_COMISION = cm.CODIGO_COMISION 
            LEFT JOIN horarios h ON pcm.CODIGO_HORARIO = h.CODIGO_HORARIO 
            WHERE pcm.DNI_PERSONA = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $dniProfesor);
        $stmt->execute();

        $result = $stmt->get_result();
        $materias = $result->fetch_all(MYSQLI_ASSOC);

        if (empty($materias)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se encontraron materias.'
            ]);
            exit();
        }

        echo json_encode([
            'status' => 'success',
            'materias' => $materias
        ]);
        exit();     

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error interno: ' . $e->getMessage()
    ]);
    exit();
}
