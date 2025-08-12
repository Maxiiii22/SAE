<?php
require_once '../../../config/db_connection.php';
include '../../../config/config.php';

if (!isset($_GET['codMateria'])) {
    echo '<p>Error: No se proporcionó un codMateria válido.</p>';
    exit();
}
$codMateria = $_GET['codMateria'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Materia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/admin/css/estilo_admin.css">
</head>

<body class="mybody">
    <div class="container mt-4">
        <h3 class="text-white">Detalle de Materia</h3>
        <form id="formDetalleMateria">
            <div class="row">
                <div class="col-md-6 mt-2">
                    <label class="text-white">Codigo:</label>
                    <input type="text" id="codMateria" class="form-control" disabled>
                </div>
                <div class="col-md-6 mt-2">
                    <label class="text-white">Nombre:</label>
                    <input type="text" id="nombreMateria" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
        </form>

        <div id="mensaje" class="mt-3 text-white"></div>
    </div>

</body>

</html>