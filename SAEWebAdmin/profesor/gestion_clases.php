<?php
require_once '../config/db_connection.php';
include '../config/config.php';
include '../shared/navbar.php';

// Verificar si el usuario tiene DNI en la sesión
if (!isset($_SESSION['dni_persona'])) {
    echo '<p>Error: No se encontró el DNI en la sesión.</p>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Clases</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/profesor/css/estilo_gestion_clases.css">
</head>
<body class="mybody">
    <h3 class="mb-4 text-center">Mis materias</h3>

    <div id="cards-container" class="row row-cols-1 row-cols-md-3 g-4 mb-5"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const apiUrl = '<?php echo BASE_URL; ?>/profesor/api/gestion_clases_api.php';

            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Error al obtener los datos de la API');
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success' && data.materias) {
                        cargarCards(data.materias);
                    } else {
                        mostrarError(data.message || 'No se encontraron materias.');
                    }
                })
                .catch(error => mostrarError(error.message));

            function cargarCards(materias) {
                const container = document.getElementById('cards-container');
                container.innerHTML = '';

                materias.forEach(materia => {
                    const card = `
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">${materia.NOMBRE_MATERIA}</h5>
                                    <p class="card-text">Carrera: ${materia.TITULO_ABREVIADO}</p>
                                    <p class="card-text">Comisión: ${materia.DESCRIPCION || 'N/A'}</p>
                                    <p class="card-text">Turno: ${materia.HORARIO || 'N/A'}</p>
                                    <a href="vistas/detalle_clases.php?codigo_materia=${materia.CODIGO_MATERIA}&codigo_horario=${materia.CODIGO_HORARIO || 'null'}&codigo_comision=${materia.CODIGO_COMISION || 'null'}" 

                                       class="btn btn-primary">Ver Clases</a>
                                </div>
                            </div>
                        </div>`;
                    container.insertAdjacentHTML('beforeend', card);
                });
            }

            function mostrarError(mensaje) {
                const container = document.getElementById('cards-container');
                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger text-center">${mensaje}</div>
                    </div>`;
            }
        });
    </script>
</body>
</html>
