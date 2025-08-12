document.addEventListener('DOMContentLoaded', function () {
    const BASE_URL = "http://localhost/saewebadmin";
    const apiUrlEditDel = `${BASE_URL}/profesor/api/clases_edit_del.php`;

    // Delegar eventos para Editar
    document.getElementById('tabla-clases').addEventListener('click', function (e) {
        if (e.target.classList.contains('modificar')) {
            const idClase = e.target.dataset.id;
            abrirModalEditar(idClase);
        }
    });

    // Delegar eventos para Eliminar
    document.getElementById('tabla-clases').addEventListener('click', function (e) {
        if (e.target.classList.contains('eliminar')) {
            const idClase = e.target.dataset.id;
            eliminarClase(idClase);
        }
    });

    // Abrir modal para Editar
    function abrirModalEditar(idClase) {
        fetch(`${apiUrlEditDel}?action=obtener_clase&id=${idClase}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const clase = data.clase;
                    document.getElementById('fechaClase').value = clase.FECHA;
                    document.getElementById('horaInicio').value = clase.HORA_INICIO;
                    document.getElementById('horaFin').value = clase.HORA_FIN;
                    document.getElementById('temasClase').value = clase.TEMAS;
                    document.getElementById('novedadesClase').value = clase.NOVEDADES;
                    document.getElementById('aulaClase').value = clase.AULA;
                    document.getElementById('idClase').value = idClase;

                    const modal = new bootstrap.Modal(document.getElementById('modalNuevaClase'));
                    document.getElementById('modalNuevaClaseLabel').innerText = 'Editar Clase';
                    modal.show();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'No se pudieron cargar los datos de la clase.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => Swal.fire({
                title: 'Error',
                text: 'Error al obtener los datos de la clase.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            }));
    }

    // Guardar cambios en la clase
    document.getElementById('btnGuardarClase').addEventListener('click', function () {
        const idClase = document.getElementById('idClase').value;
        const formData = new FormData(document.getElementById('formNuevaClase'));

        if (idClase) {
            // Edición
            formData.append('id', idClase);
            fetch(`${apiUrlEditDel}?action=editar`, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Éxito',
                            text: 'Clase actualizada correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'Error al actualizar la clase.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(error => Swal.fire({
                    title: 'Error',
                    text: 'Error al guardar cambios.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                }));
        } else {
            // Crear (se manejará desde detalle_clases.php, no aquí)
            console.error("Intento de creación detectado en edición. Esto no debería ocurrir aquí.");
        }
    });

    // Eliminar clase
    function eliminarClase(idClase) {
        Swal.fire({
            title: '¿Está seguro?',
            text: 'No podrá revertir esta acción.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${apiUrlEditDel}?action=eliminar`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: idClase })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Eliminado',
                                text: 'Clase eliminada correctamente.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'Error al eliminar la clase.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    })
                    .catch(error => Swal.fire({
                        title: 'Error',
                        text: 'Error al eliminar la clase.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }));
            }
        });
    }
});
