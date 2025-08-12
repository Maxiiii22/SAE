<?php
require_once '../../../config/db_connection.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $idCarrera = isset($_GET['id_carrera']) ? $_GET['id_carrera'] : null;

    // **Consulta para obtener las materias**
    $query = "SELECT m.CODIGO_MATERIA, m.NOMBRE_MATERIA, c.DESCRIPCION 
              FROM materias AS m
              INNER JOIN carreras AS c ON m.ID_CARRERA = c.ID_CARRERA";
    
    // Si se especifica un id_carrera, agregar la condición WHERE para filtrar por carrera
    if ($idCarrera) {
        $query .= " WHERE m.ID_CARRERA = '" . mysqli_real_escape_string($conn, $idCarrera) . "'";
    }
    
    $result = mysqli_query($conn, $query);
    $materias = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $materias[] = [
            'id_materia' => $row['CODIGO_MATERIA'],
            'nombre_materia' => $row['NOMBRE_MATERIA'],
            'descripcion' => $row['DESCRIPCION']
        ];
    }
    
    // **Obtener la lista de carreras**
    $queryCarreras = "SELECT ID_CARRERA, TITULO_ABREVIADO, DESCRIPCION FROM carreras";
    $resultCarreras = mysqli_query($conn, $queryCarreras);
    $carreras = [];
    
    while ($row = mysqli_fetch_assoc($resultCarreras)) {
        $carreras[] = [
            'ID_CARRERA' => $row['ID_CARRERA'],
            'TITULO_ABREVIADO' => $row['TITULO_ABREVIADO'],
            'DESCRIPCION' => $row['DESCRIPCION']
        ];
    }
    
    echo json_encode(["materias" => $materias, "carreras" => $carreras]);
    exit();
}


if ($method === 'POST') {
    // **Obtener los datos enviados**
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['idMateria'], $input['nombreMateria'], $input['idCarrera']) || empty($input['idMateria']) || empty($input['nombreMateria']) || empty($input['idCarrera'])) {
        echo json_encode(["exito" => false, "mensaje" => "Datos inválidos o campos vacíos."]);
        exit();
    }

    $idCarrera = $input['idCarrera'];
    $idMateria = trim($input['idMateria']);
    $nombreMateria = trim($input['nombreMateria']);

    // **Validar que los datos no estén vacíos**
    if (empty($idMateria) || empty($nombreMateria) || empty($idCarrera)) {
        echo json_encode(["exito" => false, "mensaje" => "Todos los campos son obligatorios."]);
        exit();
    }

    // **Verificar si la materia ya existe en la tabla materias**
    $queryCheck = "SELECT COUNT(*) FROM materias WHERE NOMBRE_MATERIA = ? OR CODIGO_MATERIA = ?";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bind_param("si", $nombreMateria, $idMateria);
    $stmtCheck->execute();
    $stmtCheck->bind_result($existe);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($existe > 0) {
        echo json_encode(["exito" => false, "mensaje" => "Ya hay una materia ingresada en el sistema con ese nombre o ID."]);
        exit();
    }

    // **Insertar la nueva materia en la base de datos**
    $queryInsert = "INSERT INTO materias (CODIGO_MATERIA,NOMBRE_MATERIA,ID_CARRERA) VALUES (?,?,?)";
    $stmtInsert = $conn->prepare($queryInsert);

    if (!$stmtInsert) {
        echo json_encode(["exito" => false, "mensaje" => "Error en la preparación de la consulta: " . $conn->error]);
        exit();
    }

    $stmtInsert->bind_param("isi", $idMateria, $nombreMateria, $idCarrera);
    
    if ($stmtInsert->execute()) {
        echo json_encode(["exito" => true, "mensaje" => "Materia agregada correctamente."]);
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Error al insertar la materia: " . $stmtInsert->error]);
    }

    $stmtInsert->close();
    exit();
}

echo json_encode(["exito" => false, "mensaje" => "Método no permitido."]);

?>
