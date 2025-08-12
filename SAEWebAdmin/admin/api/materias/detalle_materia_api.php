<?php
require_once '../../../config/db_connection.php';

// ✅ GET (cargar datos)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['codMateria']) && isset($_GET['fetch'])) {
    header('Content-Type: application/json');
    $codMateria = $_GET['codMateria'];

    $query = "SELECT CODIGO_MATERIA, NOMBRE_MATERIA, ID_CARRERA FROM materias WHERE CODIGO_MATERIA = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $codMateria);
    $stmt->execute();
    $result = $stmt->get_result();
    $materia = $result->fetch_assoc();

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

    echo json_encode(["materia" => $materia, "carreras" => $carreras]);
    exit;
}

// ✅ POST (guardar datos)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['codMateria'])) {
        echo json_encode(["exito" => false, "mensaje" => "Datos inválidos"]);
        exit();
    }

    $idCarrera = $input['idCarrera'];
    $codMateria = $input['codMateria'];
    $nombreMateria = $input['nombreMateria'];

    $conn->begin_transaction();

    try {
        $query = "UPDATE materias SET ID_CARRERA = ?, NOMBRE_MATERIA = ? WHERE CODIGO_MATERIA = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isi", $idCarrera, $nombreMateria, $codMateria);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        echo json_encode(["exito" => true, "mensaje" => "Materia actualizada correctamente"]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["exito" => false, "mensaje" => "Error al actualizar: " . $e->getMessage()]);
    }

    exit;
}
?>
