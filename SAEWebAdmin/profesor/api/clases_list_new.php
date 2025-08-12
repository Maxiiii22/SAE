<?php
require_once '../../config/db_connection.php';
include '../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

session_start();

if (!isset($_SESSION['dni_persona'])) {
    echo json_encode(['status' => 'error', 'message' => 'DNI no encontrado en la sesión.']);
    exit();
}

$dniProfesor = $_SESSION['dni_persona'];

// Obtener clases
if ($_GET['action'] === 'obtener_clases' && isset($_GET['codigo_materia']) && isset($_GET['codigo_horario']) && isset($_GET['codigo_comision'])) {
    $codigoMateria = intval($_GET['codigo_materia']);
    $codigoHorario = $_GET['codigo_horario'] === 'null' ? null : intval($_GET['codigo_horario']);
    $codigoComision = $_GET['codigo_comision'] === 'null' ? null : intval($_GET['codigo_comision']);

    // Obtener el CODIGO_USUARIO del profesor
    $sqlUsuario = "SELECT CODIGO_USUARIO FROM usuarios WHERE DNI_PERSONA = ?";
    $stmtUsuario = $conn->prepare($sqlUsuario);
    $stmtUsuario->bind_param("i", $dniProfesor);
    $stmtUsuario->execute();
    $usuario = $stmtUsuario->get_result()->fetch_assoc();
    $stmtUsuario->close();

    if (!$usuario) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        exit();
    }

    $codigoUsuario = $usuario['CODIGO_USUARIO'];

    // Obtener el nombre de la materia
    $sqlMateria = "SELECT NOMBRE_MATERIA FROM materias WHERE CODIGO_MATERIA = ?";
    $stmtMateria = $conn->prepare($sqlMateria);
    $stmtMateria->bind_param("i", $codigoMateria);
    $stmtMateria->execute();
    $materia = $stmtMateria->get_result()->fetch_assoc();
    $stmtMateria->close();

    if (!$materia) {
        echo json_encode(['status' => 'error', 'message' => 'Materia no encontrada.']);
        exit();
    }

    $nombreMateria = $materia['NOMBRE_MATERIA'];

    // Obtener el Nombre y apellido del profesor
    $sqlProfesor = "
        SELECT p.NOMBRE_PERSONA, p.APELLIDO_PERSONA
        FROM personas p
        WHERE p.DNI_PERSONA = ?
        LIMIT 1
        ";
    $stmtProfesor = $conn->prepare($sqlProfesor);
    $stmtProfesor->bind_param('i', $dniProfesor);
    $stmtProfesor->execute();
    $resultProfesor = $stmtProfesor->get_result();
    $profesor = $resultProfesor->fetch_assoc();

    $nombreProfesor = $profesor ? $profesor['NOMBRE_PERSONA'] . ' ' . $profesor['APELLIDO_PERSONA'] : 'No asignado';

    // Obtener la descripcion de la comision
    $sqlDescripcionComision = "SELECT DESCRIPCION FROM comisiones WHERE CODIGO_COMISION = ?";
    $stmtDescripcionComision = $conn->prepare($sqlDescripcionComision);
    $stmtDescripcionComision->bind_param("i", $codigoComision);
    $stmtDescripcionComision->execute();
    $DescripcionComision = $stmtDescripcionComision->get_result()->fetch_assoc();
    $stmtDescripcionComision->close();

    if (!$DescripcionComision) {
        echo json_encode(['status' => 'error', 'message' => 'Comision no encontrada.']);
        exit();
    }

    $nombreComision = $DescripcionComision['DESCRIPCION'];

    // Obtener la descripcion del horario
    $sqlDescripcionHorario = "SELECT HORARIO FROM horarios WHERE CODIGO_HORARIO = ?";
    $stmtDescripcionHorario = $conn->prepare($sqlDescripcionHorario);
    $stmtDescripcionHorario->bind_param("i", $codigoHorario);
    $stmtDescripcionHorario->execute();
    $DescripcionHorario = $stmtDescripcionHorario->get_result()->fetch_assoc();
    $stmtDescripcionHorario->close();

    if (!$DescripcionHorario) {
        echo json_encode(['status' => 'error', 'message' => 'Horario no encontrado.']);
        exit();
    }

    $descripcion_horario = $DescripcionHorario['HORARIO'];


    // Obtener las clases específicas
    $sqlClases = "SELECT c.ID_CLASE, c.FECHA, h.HORARIO AS HORARIO, c.HORA_INICIO, c.HORA_FIN, 
    c.TEMAS, c.NOVEDADES, c.AULA, c.ARCHIVOS
    FROM clases c
    LEFT JOIN horarios h ON c.CODIGO_HORARIO = h.CODIGO_HORARIO
    WHERE c.CODIGO_MATERIA = ? 
    AND c.CODIGO_USUARIO = ? 
    AND (c.CODIGO_HORARIO = ? OR c.CODIGO_HORARIO IS NULL) 
    AND (c.CODIGO_COMISION = ? OR c.CODIGO_COMISION IS NULL)";
    $stmtClases = $conn->prepare($sqlClases);
    $stmtClases->bind_param("iiii", $codigoMateria, $codigoUsuario, $codigoHorario, $codigoComision);
    $stmtClases->execute();
    $clases = $stmtClases->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtClases->close();

    echo json_encode(['status' => 'success', 'nombre_materia' => $nombreMateria, 'clases' => $clases, 'nombre_profesor' => $nombreProfesor, 'nombre_comision' => $nombreComision, 'descripcion_horario' => $descripcion_horario]);
    exit();
}

// Crear clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crear_clase') {
    $codigoMateria = intval($_POST['codigo_materia']);
    $codigoHorario = intval($_POST['codigo_horario']);
    $codigoComision = intval($_POST['codigo_comision']);
    $fecha = $_POST['fechaClase'];
    $horaInicio = $_POST['horaInicio'];
    $horaFin = $_POST['horaFin'];
    $temas = $_POST['temasClase'];
    $novedades = $_POST['novedadesClase'];
    $aula = $_POST['aulaClase'];
    $archivoNombre = $_FILES['archivosClase']['name'] ?? null;

    $sqlUsuario = "SELECT CODIGO_USUARIO FROM usuarios WHERE DNI_PERSONA = ?";
    $stmtUsuario = $conn->prepare($sqlUsuario);
    $stmtUsuario->bind_param("i", $dniProfesor);
    $stmtUsuario->execute();
    $usuario = $stmtUsuario->get_result()->fetch_assoc();
    $stmtUsuario->close();

    if (!$usuario) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        exit();
    }

    $codigoUsuario = $usuario['CODIGO_USUARIO'];

    if ($archivoNombre) {
        $uploadDir = '../../assets/docs/';
        $uploadFile = $uploadDir . basename($archivoNombre);
        if (!move_uploaded_file($_FILES['archivosClase']['tmp_name'], $uploadFile)) {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo.']);
            exit();
        }
    }

    $sqlInsert = "INSERT INTO clases (CODIGO_USUARIO, CODIGO_MATERIA, FECHA, CODIGO_HORARIO, HORA_INICIO, HORA_FIN, TEMAS, NOVEDADES, CODIGO_COMISION, AULA, ARCHIVOS)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param(
        "iisissssiss",
        $codigoUsuario,
        $codigoMateria,
        $fecha,
        $codigoHorario,
        $horaInicio,
        $horaFin,
        $temas,
        $novedades,
        $codigoComision,
        $aula,
        $archivoNombre
    );

    if ($stmtInsert->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Clase creada exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al crear la clase.']);
    }
    $stmtInsert->close();
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Acción no válida.']);
exit();
