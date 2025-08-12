<?php
require_once '../../../config/db_connection.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
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
    
    echo json_encode(["carreras" => $carreras]);
    exit();
}


if ($method === 'POST') {
    // **Obtener los datos enviados**
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['nombreCarrera'], $input['abreviacion']) || empty($input['nombreCarrera']) || empty($input['abreviacion'])) {
        echo json_encode(["exito" => false, "mensaje" => "Datos inválidos o campos vacíos."]);
        exit();
    }

    $nombreCarrera = trim($input['nombreCarrera']);
    $abreviacion = trim($input['abreviacion']);

    // **Validar que los datos no estén vacíos**
    if (empty($nombreCarrera) || empty($abreviacion)) {
        echo json_encode(["exito" => false, "mensaje" => "Todos los campos son obligatorios."]);
        exit();
    }

    // **Verificar si la materia ya existe en la tabla materias**
    $queryCheck = "SELECT COUNT(*) FROM carreras WHERE DESCRIPCION = ? OR TITULO_ABREVIADO = ?";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bind_param("ss", $nombreCarrera, $abreviacion);
    $stmtCheck->execute();
    $stmtCheck->bind_result($existe);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($existe > 0) {
        echo json_encode(["exito" => false, "mensaje" => "Ya hay una carrera ingresada en el sistema con ese nombre."]);
        exit();
    }

    // **Insertar la nueva materia en la base de datos**
    $queryInsert = "INSERT INTO carreras (TITULO_ABREVIADO,DESCRIPCION) VALUES (?,?)";
    $stmtInsert = $conn->prepare($queryInsert);

    if (!$stmtInsert) {
        echo json_encode(["exito" => false, "mensaje" => "Error en la preparación de la consulta: " . $conn->error]);
        exit();
    }

    $stmtInsert->bind_param("ss", $abreviacion, $nombreCarrera);
    
    if ($stmtInsert->execute()) {
        echo json_encode(["exito" => true, "mensaje" => "Carrera agregada correctamente."]);
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Error al insertar la carrera: " . $stmtInsert->error]);
    }

    $stmtInsert->close();
    exit();
}

echo json_encode(["exito" => false, "mensaje" => "Método no permitido."]);

?>
