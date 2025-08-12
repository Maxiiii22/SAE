<?php 
require_once '../config/db_connection.php'; 
include '../config/config.php'; 
include '../shared/navbar.php'; 

if (!isset($_SESSION['dni_persona'])) {
    echo '<p>Error: No se encontró el DNI en la sesión.</p>';
    exit();
}

$dniPersona = $_SESSION['dni_persona'];

// Obtener los valores de los filtros si existen en la URL
$filtroMateria = isset($_GET['filtro-materia']) ? $_GET['filtro-materia'] : '';
$filtroProfesor = isset($_GET['filtro-profe']) ? $_GET['filtro-profe'] : '';
$filtroComision = isset($_GET['filtro-comision']) ? $_GET['filtro-comision'] : '';
$filtroTurno = isset($_GET['filtro-turno']) ? $_GET['filtro-turno'] : '';
$filtroAnio = isset($_GET['filtro-anio']) ? $_GET['filtro-anio'] : '';
?>

<!DOCTYPE html> 
<html lang="es"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Gestión de Carreras</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/jefecarrera/css/estilo_gestion_carreras.css"> 
</head> 
<body class="mybody"> 
    <div class="container mt-5">
        <h3 class="text-center">Consulta de clases</h3>
        
        <!-- Información de la carrera -->
        <div id="info-carrera" class="my-4">
            <div class="d-flex align-items-center">
                <h4 id="titulo-carrera"></h4>
                <h4 id="descripcion-carrera" ></h4>
                <select name="" id="selectCarrera" style="width: 18px;">
                </select>
            </div>

            <div class="d-flex align-items-center mt-2">
                <a href="<?php echo BASE_URL; ?>/jefecarrera/vistas/vista_profesores.php" class="btn-efecto-profesores">
                    <span class="material-icons">badge</span> Profesores de la Carrera
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="mb-4">
            <form id="filtros-form" class="row g-3 align-items-center">
                <div class="col-md-2">
                    <label for="filtro-materia" class="form-label">Materia</label>
                    <select id="filtro-materia" class="form-select" name="filtro-materia" data-selected="<?php echo htmlspecialchars($filtroMateria); ?>">
                        <option value="">Todas</option>
                        <!-- Las opciones de materias se agregarán dinámicamente desde JS -->
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtro-profe" class="form-label">Profesor</label>
                    <select id="filtro-profe" class="form-select" name="filtro-profe" data-selected="<?php echo htmlspecialchars($filtroProfesor); ?>">
                        <option value="">Todos</option>
                        <!-- Las opciones de profesores se agregarán dinámicamente desde JS -->
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtro-comision" class="form-label">Comisión</label>
                    <select id="filtro-comision" class="form-select" name="filtro-comision" data-selected="<?php echo htmlspecialchars($filtroComision); ?>">
                        <option value="">Todas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro-turno" class="form-label">Turno</label>
                    <select id="filtro-turno" class="form-select" name="filtro-turno">
                        <option value="">Todos</option>
                        <option value="Mañana" <?php echo ($filtroTurno == 'Mañana') ? 'selected' : ''; ?>>Mañana</option>
                        <option value="Tarde" <?php echo ($filtroTurno == 'Tarde') ? 'selected' : ''; ?>>Tarde</option>
                        <option value="Noche" <?php echo ($filtroTurno == 'Noche') ? 'selected' : ''; ?>>Noche</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro-anio" class="form-label">Año</label>
                    <select id="filtro-anio" class="form-select" name="filtro-anio">
                        <option value="">Todos</option>
                        <option value="1" <?php echo ($filtroAnio == '1') ? 'selected' : ''; ?>>1°</option>
                        <option value="2" <?php echo ($filtroAnio == '2') ? 'selected' : ''; ?>>2°</option>
                        <option value="3" <?php echo ($filtroAnio == '3') ? 'selected' : ''; ?>>3°</option>
                    </select>
                </div>
            </form>
        </div>


        <!-- Tabla de materias -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cod Materia</th>
                    <th>Profesor</th>
                    <th>Nombre Materia</th>
                    <th>Comisión</th>
                    <th>Día</th>
                    <th>Horario</th>
                    <th>Turno</th>
                    <th>Año</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-materias"></tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/jefecarrera/js/gestion_carreras.js"></script>
</body> 
</html>
