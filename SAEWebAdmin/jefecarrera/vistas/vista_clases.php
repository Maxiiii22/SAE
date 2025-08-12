<?php
require_once '../../config/db_connection.php';
include '../../config/config.php';
include '../../shared/navbar.php';

if (!isset($_GET['codigo_materia'])) {
    echo '<p>Error: Código de materia no proporcionado.</p>';
    exit();
}

$codigoMateria = intval($_GET['codigo_materia']);
$comision = isset($_GET['comision']) ? $_GET['comision'] : '';
$horario = isset($_GET['horario']) ? $_GET['horario'] : '';
$filtroMateria = isset($_GET['filtro-materia']) ? $_GET['filtro-materia'] : '';
$filtroComision = isset($_GET['filtro-comision']) ? $_GET['filtro-comision'] : '';
$filtroTurno = isset($_GET['filtro-turno']) ? $_GET['filtro-turno'] : '';
$filtroAnio = isset($_GET['filtro-anio']) ? $_GET['filtro-anio'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Clases</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/profesor/css/estilo_gestion_clases.css">
</head>

<body class="mybody">
    <div id="clases-container">
        <!-- Información organizada de la materia, comisión y horario -->
        <div class="mb-4">
            <h4 id="titulo-materia" >Clases de <span id="nombre-materia">...</span></h4>
            <p class="pinfo"><strong>Comisión:</strong> <span id="descripcion-comision"></span></p>
            <p class="pinfo"><strong>Horario:</strong> <span id="descripcion-horario"></span></p>
            <p class="pinfo"><strong>Profesor/a:</strong> <span id="nombre-profesor"></span></p>

        </div>
        
        <input type="search" id="buscar-clases" class="form-control mb-3" placeholder="Buscar por palabra clave">
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nro de Clase</th>
                    <th>Fecha</th>
                    <th>Horario</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Temas</th>
                    <th>Novedades</th>
                    <th>Aula</th>
                    <th>Archivos</th>
                </tr>
            </thead>
            <tbody id="tabla-clases"></tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
                document.addEventListener('DOMContentLoaded', function () {
    const apiUrl = `<?php echo BASE_URL; ?>/jefecarrera/api/vista_clases_api.php?codigo_materia=<?php echo $codigoMateria; ?>&comision=<?php echo $comision; ?>&horario=<?php echo $horario; ?>&filtro-materia=<?php echo $filtroMateria; ?>&filtro-comision=<?php echo $filtroComision; ?>&filtro-turno=<?php echo $filtroTurno; ?>&filtro-anio=<?php echo $filtroAnio; ?>`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            console.log(data); // Verificar los datos recibidos
            if (data.status === 'success') {
                // Mostrar los datos de la materia, comisión y horario
                document.getElementById('nombre-materia').textContent = data.nombre_materia;
                document.getElementById('descripcion-comision').textContent = data.comision_descripcion;
                document.getElementById('descripcion-horario').textContent = data.horario_descripcion;
                document.getElementById('nombre-profesor').textContent = data.nombre_profesor;


                cargarTabla(data.clases, data.horario_descripcion);
            } else {
                mostrarError(data.message || 'No se encontraron clases.');
            }
        })
        .catch(error => mostrarError('Error en la conexión con la API.'));

    function cargarTabla(clases, horario_descripcion) {
        const tbody = document.getElementById('tabla-clases');
        tbody.innerHTML = '';

        if (clases.length === 0) {
            // Mostrar un mensaje cuando no hay clases
            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">No hay clases disponibles.</td></tr>`;
            return;
        }

        clases.forEach((clase, index) => {
            const fila = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${clase.FECHA}</td>
                    <td>${horario_descripcion || 'N/A'}</td> <!-- Mostramos la descripción del horario -->
                    <td>${clase.HORA_INICIO}</td>
                    <td>${clase.HORA_FIN}</td>
                    <td>${clase.TEMAS || 'N/A'}</td>
                    <td>${clase.NOVEDADES || 'N/A'}</td>
                    <td>${clase.AULA || 'N/A'}</td>
                    <td>${clase.ARCHIVOS ? `<a href="/SAEWebadmin/assets/docs/${clase.ARCHIVOS}" target="_blank">Ver</a>` : 'N/A'}</td>
                </tr>`;
            tbody.insertAdjacentHTML('beforeend', fila);
        });
    }

    function mostrarError(mensaje) {
        const tbody = document.getElementById('tabla-clases');
        tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">${mensaje}</td></tr>`;
    }

    // Función de búsqueda por palabra clave
    const buscarInput = document.getElementById('buscar-clases');
    buscarInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tabla-clases tr');
        filas.forEach(fila => {
            const cells = fila.getElementsByTagName('td');
            let match = false;
            for (let i = 0; i < cells.length; i++) {
                if (cells[i].textContent.toLowerCase().includes(keyword)) {
                    match = true;
                    break;
                }
            }
            fila.style.display = match ? '' : 'none';
        });
        
    });
    
});

    </script>
</body>

</html>
