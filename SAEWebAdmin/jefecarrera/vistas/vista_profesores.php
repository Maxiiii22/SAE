<?php
require_once '../../config/db_connection.php';
include '../../config/config.php';
include '../../shared/navbar.php';

if (!isset($_SESSION['dni_persona'])) {
    echo '<p>Error: No se encontró el DNI en la sesión.</p>';
    exit();
}

$dniPersona = $_SESSION['dni_persona'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Profesores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/jefecarrera/css/estilo_gestion_carreras.css">
</head>

<body class="mybody">
    <div class="container mt-5">
        <h3 class="text-center">Consulta de Profesores</h3>
        <hr>

        <!-- Filtros -->
        <div class="mb-3">
            <form id="filtros-form" class="row gy-2 gx-3 align-items-center">
                <div class="col-md-3">
                    <label for="filtro-profesor" class="form-label">Profesor</label>
                    <select id="filtro-profesor" class="form-select">
                        <option value="">Todas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro-materia" class="form-label">Materia</label>
                    <select id="filtro-materia" class="form-select">
                        <option value="">Todas</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="filtro-turno" class="form-label">Turno</label>
                    <select id="filtro-turno" class="form-select">
                        <option value="">Todos</option>
                        <option value="Mañana">Mañana</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noche">Noche</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtro-anio" class="form-label">Año de carrera</label>
                    <select id="filtro-anio" class="form-select">
                        <option value="">Todos</option>
                        <option value="1">1°</option>
                        <option value="2">2°</option>
                        <option value="3">3°</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtro-carrera" class="form-label">Carrera</label>
                    <select id="filtro-carrera" class="form-select">
                        <option value="">Todas</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Cards de Profesores -->
        <div id="cards-container" class="row">
            <!-- Las cards se generarán dinámicamente mediante JS -->
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/jefecarrera/js/vista_profesores.js"></script>
</body>

</html>
