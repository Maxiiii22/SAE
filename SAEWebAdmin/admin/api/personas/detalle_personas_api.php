<?php
require_once '../../../config/db_connection.php';
header('Content-Type: application/json');

if (!isset($_GET['dni'])) {
    echo json_encode(["error" => "No se proporcionó un DNI válido"]);
    exit();
}

$dni = $_GET['dni'];

// Obtener datos de la persona y su rol
$query = "SELECT p.DNI_PERSONA, p.NOMBRE_PERSONA, p.APELLIDO_PERSONA, p.CODIGO_ROL, 
                 r.DESCRIPCION AS DESCRIPCION_ROL,
                 u.MAIL_USUARIO, u.CONTRASEÑA_USUARIO
          FROM personas p
          LEFT JOIN usuarios u ON p.DNI_PERSONA = u.DNI_PERSONA
          LEFT JOIN roles r ON p.CODIGO_ROL = r.CODIGO_ROL
          WHERE p.DNI_PERSONA = '$dni'";

$result = mysqli_query($conn, $query);
$persona = mysqli_fetch_assoc($result);

// Asegurar valores vacíos si no existen en la BD
$persona['MAIL_USUARIO'] = $persona['MAIL_USUARIO'] ?? "";
$persona['CONTRASEÑA_USUARIO'] = $persona['CONTRASEÑA_USUARIO'] ?? "";

// Obtener materias y carreras asociadas a la persona con descripciones
$queryMaterias = "SELECT 
                    pcm.ID_CARRERA, c.TITULO_ABREVIADO, c.DESCRIPCION AS DESC_CARRERA,
                    pcm.CODIGO_MATERIA, m.NOMBRE_MATERIA,
                    pcm.CODIGO_COMISION, co.DESCRIPCION AS DESC_COMISION,
                    pcm.CODIGO_HORARIO, h.HORARIO
                  FROM personas_carreras_materias pcm
                  LEFT JOIN carreras c ON pcm.ID_CARRERA = c.ID_CARRERA
                  LEFT JOIN materias m ON pcm.CODIGO_MATERIA = m.CODIGO_MATERIA
                  LEFT JOIN comisiones co ON pcm.CODIGO_COMISION = co.CODIGO_COMISION
                  LEFT JOIN horarios h ON pcm.CODIGO_HORARIO = h.CODIGO_HORARIO
                  WHERE pcm.DNI_PERSONA = '$dni'";

$resultMaterias = mysqli_query($conn, $queryMaterias);
$materias = [];
while ($row = mysqli_fetch_assoc($resultMaterias)) {
    $materias[] = $row;
}

$persona['materias'] = $materias;

$queryCarrerasJefe = "SELECT DISTINCT pcm.ID_CARRERA, c.DESCRIPCION  # Traemos las carreras asociados al jefe de area
                      FROM personas_carreras_materias pcm
                      INNER JOIN carreras c ON pcm.ID_CARRERA = c.ID_CARRERA
                      WHERE pcm.DNI_PERSONA = '$dni'
                      AND pcm.CODIGO_MATERIA IS NULL
                      AND pcm.CODIGO_COMISION IS NULL
                      AND pcm.CODIGO_HORARIO IS NULL";

$resultCarrerasJefe = mysqli_query($conn, $queryCarrerasJefe);
$carrerasAsignadas = [];
while ($row = mysqli_fetch_assoc($resultCarrerasJefe)) {
    $carrerasAsignadas[] = $row;
}

$persona['carrerasAsignadas'] = $carrerasAsignadas;

// Obtener listas para los selects
$rolesQuery = "SELECT * FROM roles";
$rolesResult = mysqli_query($conn, $rolesQuery);
$roles = mysqli_fetch_all($rolesResult, MYSQLI_ASSOC);

$carrerasQuery = "SELECT * FROM carreras";
$carrerasResult = mysqli_query($conn, $carrerasQuery);
$carreras = mysqli_fetch_all($carrerasResult, MYSQLI_ASSOC);

$materiasQuery = "SELECT * FROM materias";
$materiasResult = mysqli_query($conn, $materiasQuery);
$materiasList = mysqli_fetch_all($materiasResult, MYSQLI_ASSOC);

$comisionesQuery = "SELECT * FROM comisiones";
$comisionesResult = mysqli_query($conn, $comisionesQuery);
$comisiones = mysqli_fetch_all($comisionesResult, MYSQLI_ASSOC);

$horariosQuery = "SELECT * FROM horarios";
$horariosResult = mysqli_query($conn, $horariosQuery);
$horarios = mysqli_fetch_all($horariosResult, MYSQLI_ASSOC);

// Incluir listas de opciones en la respuesta
$persona['roles'] = $roles;
$persona['carreras'] = $carreras;
$persona['materiasList'] = $materiasList;
$persona['comisiones'] = $comisiones;
$persona['horarios'] = $horarios;

echo json_encode($persona);
?>
