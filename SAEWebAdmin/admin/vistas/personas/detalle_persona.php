<?php
require_once '../../../config/db_connection.php';
include '../../../config/config.php';

if (!isset($_GET['dni'])) {
    echo '<p>Error: No se proporcionó un DNI válido.</p>';
    exit();
}
$dni = $_GET['dni'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Persona</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/admin/css/estilo_admin.css">
</head>

<body class="mybody">
    <div class="container mt-4">
        <h3 class="text-white">Detalle de Persona</h3>
        <form id="formDetallePersona">
            <div class="row">
                <div class="col-md-6">
                    <label class="text-white">DNI:</label>
                    <input type="text" id="dni" class="form-control" disabled>
                </div>
                <div class="col-md-6">
                    <label class="text-white">Apellido:</label>
                    <input type="text" id="apellido" class="form-control">
                </div>
                <div class="col-md-6 mt-2">
                    <label class="text-white">Nombre:</label>
                    <input type="text" id="nombre" class="form-control">
                </div>
                <div class="col-md-6 mt-2">
                    <label class="text-white">Rol:</label>
                    <select id="codigo_rol" class="form-select"></select>
                </div>

                <!-- Contenedor para mail y contraseña -->
                <div class="col-12 mt-2" id="usuarioContainer" style="display: none;">
                    <div class="alert alert-warning" id="mensajeUsuarioNoRegistrado" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i> Usuario no registrado, no se actualizará el email ni la contraseña.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-white">Email:</label>
                            <input type="email" id="mail_usuario" class="form-control" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="text-white">Contraseña:</label>
                            <input type="password" id="contrasena_usuario" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-table">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="text-white mt-4">Materias y Carreras</h4>
                    <button type="button" class="btn btn-success" id="btnAñadirMateria" style="display:none;"><i class="fas fa-plus"></i> Asignarle Nueva Materia</button>
                    <button type="button" class="btn btn-success" id="btnAsignarCarrera" style="display:none;"><i class="fas fa-plus"></i> Asignarle Nueva Carrera</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
                        <thead id="headTablaMaterias">
                            <tr class="text-center">
                                <th>Carrera</th>
                                <th>Materia</th>
                                <th>Comisión</th>
                                <th>Horario</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tablaMaterias"></tbody>
                    </table>
                </div>
            </div>

            <!-- Botones -->
            <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
            <button type="button" class="btn btn-danger mt-3" id="btnEliminar">Eliminar Persona</button>
        </form>

        <div id="mensaje" class="mt-3 text-white"></div>
    </div>

</body>

</html>