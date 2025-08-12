<?php
require_once '../../config/db_connection.php';
include '../../config/config.php';
include '../../shared/navbar.php';

if (!isset($_GET['codigo_materia'])) {
    echo '<p>Error: Código de materia no proporcionado.</p>';
    exit();
}

$codigoMateria = intval($_GET['codigo_materia']);
$codigoHorario = $_GET['codigo_horario'] === 'null' ? null : intval($_GET['codigo_horario']);
$codigoComision = $_GET['codigo_comision'] === 'null' ? null : intval($_GET['codigo_comision']);
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="mybody">
    <div id="clases-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 id="titulo-materia" class="mb-0">Clases de <span id="nombre-materia">...</span></h4>
            <button id="btn-nueva-clase" class="btn btn-success">Nueva Clase</button>
        </div>
        <p class="pinfo"><strong>Comisión:</strong> <span id="descripcion-comision"></span></p>
        <p class="pinfo"><strong>Horario:</strong> <span id="descripcion-horario"></span> hs.</p>
        <p class="pinfo"><strong>Profesor/a:</strong> <span id="nombre-profesor"></span></p>

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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-clases"></tbody>
        </table>
    </div>

    <!-- Modal para Nueva Clase -->
    <div class="modal fade" id="modalNuevaClase" tabindex="-1" aria-labelledby="modalNuevaClaseLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevaClaseLabel">Nueva Clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="formNuevaClase">
                        <input type="hidden" id="idClase" name="idClase">
                        <div class="mb-3">
                            <label for="fechaClase" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fechaClase" name="fechaClase" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="horaInicio" class="form-label">Hora Inicio</label>
                                <input type="time" class="form-control" id="horaInicio" name="horaInicio" required>
                            </div>
                            <div class="col-md-6">
                                <label for="horaFin" class="form-label">Hora Fin</label>
                                <input type="time" class="form-control" id="horaFin" name="horaFin" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="temasClase" class="form-label">Temas</label>
                            <textarea class="form-control" id="temasClase" name="temasClase" rows="1"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="novedadesClase" class="form-label">Novedades</label>
                            <textarea class="form-control" id="novedadesClase" name="novedadesClase" rows="1"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="aulaClase" class="form-label">Aula</label>
                            <input type="text" class="form-control" id="aulaClase" name="aulaClase" required>
                        </div>
                        <div class="mb-3">
                            <label for="archivosClase" class="form-label">Archivos</label>
                            <input type="file" class="form-control" id="archivosClase" name="archivosClase">
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarClase">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const apiUrl = '<?php echo BASE_URL; ?>/profesor/api/clases_list_new.php?action=obtener_clases&codigo_materia=<?php echo $codigoMateria; ?>&codigo_horario=<?php echo $codigoHorario; ?>&codigo_comision=<?php echo $codigoComision; ?>';

        // Botón para abrir el modal
        document.getElementById('btn-nueva-clase').addEventListener('click', function () {
            const modal = new bootstrap.Modal(document.getElementById('modalNuevaClase'));
            document.getElementById('formNuevaClase').reset();
            document.getElementById('idClase').value = ''; // Limpiar ID para nueva clase
            document.getElementById('modalNuevaClaseLabel').innerText = 'Nueva Clase';
            modal.show();
        });

        // Guardar clase (nueva o editada)
        document.getElementById('btnGuardarClase').addEventListener('click', function () {
            const idClase = document.getElementById('idClase').value;
            const formData = new FormData(document.getElementById('formNuevaClase'));

            if (idClase) {
                // Edición
                formData.append('id', idClase);
                fetch('<?php echo BASE_URL; ?>/profesor/api/clases_edit_del.php?action=editar', {
                    method: 'POST',
                    body: formData
                });
            } else {
                // Creación
                formData.append('codigo_materia', '<?php echo $codigoMateria; ?>');
                formData.append('codigo_horario', '<?php echo $codigoHorario; ?>');
                formData.append('codigo_comision', '<?php echo $codigoComision; ?>');
                fetch('<?php echo BASE_URL; ?>/profesor/api/clases_list_new.php?action=crear_clase', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Éxito',
                                text: 'Clase creada exitosamente.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'Error al crear la clase.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    })
                    .catch(error => Swal.fire({
                        title: 'Error',
                        text: 'Error al crear clase.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }));
            }
        });

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                console.log(data); // Verifica aquí qué está devolviendo la API
                if (data.status === 'success' && data.clases) {
                    document.getElementById('nombre-materia').textContent = data.nombre_materia;
                    document.getElementById('nombre-profesor').textContent = data.nombre_profesor;
                    document.getElementById('descripcion-comision').textContent = data.nombre_comision;
                    document.getElementById('descripcion-horario').textContent = data.descripcion_horario;
                    if (data.clases.length > 0) {
                        cargarTabla(data.clases);
                    } else {
                        mostrarMensajeNoClases();
                    }
                } else {
                    mostrarMensajeNoClases();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensajeNoClases();
            });

        function cargarTabla(clases) {
            const tbody = document.getElementById('tabla-clases');
            tbody.innerHTML = '';

            clases.forEach((clase, index) => {
                const fila = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${clase.FECHA}</td>
                        <td>${clase.HORARIO || 'N/A'}</td>
                        <td>${clase.HORA_INICIO}</td>
                        <td>${clase.HORA_FIN}</td>
                        <td>${clase.TEMAS || 'N/A'}</td>
                        <td>${clase.NOVEDADES || 'N/A'}</td>
                        <td>${clase.AULA || 'N/A'}</td>
                        <td>${clase.ARCHIVOS ? `<a href="/SAEWebadmin/assets/docs/${clase.ARCHIVOS}" target="_blank">Ver</a>` : 'N/A'}</td>
                        <td>
                            <span class="material-icons icono-accion modificar" data-id="${clase.ID_CLASE}">edit</span>
                            <span class="material-icons icono-accion eliminar" data-id="${clase.ID_CLASE}">delete</span>
                        </td>
                    </tr>`;
                tbody.insertAdjacentHTML('beforeend', fila);
            });
        }

        function mostrarMensajeNoClases() {
            const tbody = document.getElementById('tabla-clases');
            tbody.innerHTML = `<tr><td colspan="10" class="text-center">No hay clases disponibles para mostrar.</td></tr>`;
        }
});

    </script>
    <script src="<?php echo BASE_URL; ?>/profesor/js/detalle_clases_edit_del.js"></script>
</body>

</html>
