<?php
require_once '../../../config/db_connection.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // **Obtener todas las personas**
    $query = "SELECT p.DNI_PERSONA, p.NOMBRE_PERSONA, p.APELLIDO_PERSONA, p.CODIGO_ROL, 
                     r.DESCRIPCION AS DESCRIPCION_ROL,
                     CASE WHEN u.DNI_PERSONA IS NOT NULL THEN 1 ELSE 0 END AS registrado
              FROM personas p
              LEFT JOIN usuarios u ON p.DNI_PERSONA = u.DNI_PERSONA
              LEFT JOIN roles r ON p.CODIGO_ROL = r.CODIGO_ROL";

    $result = mysqli_query($conn, $query);
    $personas = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $personas[] = [
            'dni' => $row['DNI_PERSONA'],
            'nombre' => $row['NOMBRE_PERSONA'],
            'apellido' => $row['APELLIDO_PERSONA'],
            'codigo_rol' => $row['CODIGO_ROL'],
            'descripcion_rol' => $row['DESCRIPCION_ROL'],
            'registrado' => $row['registrado']
        ];
    }

    // **Obtener la lista de roles**
    $queryRoles = "SELECT CODIGO_ROL, DESCRIPCION FROM roles";
    $resultRoles = mysqli_query($conn, $queryRoles);
    $roles = [];

    while ($row = mysqli_fetch_assoc($resultRoles)) {
        $roles[] = [
            'CODIGO_ROL' => $row['CODIGO_ROL'],
            'DESCRIPCION' => $row['DESCRIPCION']
        ];
    }

    echo json_encode(["personas" => $personas, "roles" => $roles]);
    exit();
}


if ($method === 'POST') {
    // **Obtener los datos enviados**
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['dni'], $input['apellido'], $input['nombre'], $input['codigo_rol'])) {
        echo json_encode(["exito" => false, "mensaje" => "Datos inválidos."]);
        exit();
    }

    $dni = trim($input['dni']);
    $apellido = trim($input['apellido']);
    $nombre = trim($input['nombre']);
    $codigo_rol = $input['codigo_rol'];

    // **Validar que los datos no estén vacíos**
    if (empty($dni) || empty($apellido) || empty($nombre) || empty($codigo_rol)) {
        echo json_encode(["exito" => false, "mensaje" => "Todos los campos son obligatorios."]);
        exit();
    }

    // **Verificar si el DNI ya existe en la tabla personas**
    $queryCheck = "SELECT COUNT(*) FROM personas WHERE DNI_PERSONA = ?";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bind_param("i", $dni);
    $stmtCheck->execute();
    $stmtCheck->bind_result($existe);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($existe > 0) {
        echo json_encode(["exito" => false, "mensaje" => "El DNI ingresado ya existe en el sistema."]);
        exit();
    }

    // **Insertar la nueva persona en la base de datos**
    $queryInsert = "INSERT INTO personas (DNI_PERSONA, APELLIDO_PERSONA, NOMBRE_PERSONA, CODIGO_ROL) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($queryInsert);

    if (!$stmtInsert) {
        echo json_encode(["exito" => false, "mensaje" => "Error en la preparación de la consulta: " . $conn->error]);
        exit();
    }

    $stmtInsert->bind_param("issi", $dni, $apellido, $nombre, $codigo_rol);
    
    if ($stmtInsert->execute()) {
        echo json_encode(["exito" => true, "mensaje" => "Persona agregada correctamente."]);
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Error al insertar la persona: " . $stmtInsert->error]);
    }

    $stmtInsert->close();
    exit();
}

echo json_encode(["exito" => false, "mensaje" => "Método no permitido."]);
?>
